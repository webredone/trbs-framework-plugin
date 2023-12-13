import dotenv from 'dotenv';
dotenv.config();

import gulp from 'gulp';
import gulpif from 'gulp-if';
import plumber from 'gulp-plumber';
import notify from 'gulp-notify';
import size from 'gulp-size';
import { createGulpEsbuild } from 'gulp-esbuild';
import * as sassCompiler from 'sass';
import gulpSass from 'gulp-sass';
const sass = gulpSass(sassCompiler);
import sourcemaps from 'gulp-sourcemaps';
import rename from 'gulp-rename';
import autoprefixer from 'gulp-autoprefixer';
import browserSync from 'browser-sync';
import gcmq from 'gulp-group-css-media-queries';
import minifycss from 'gulp-uglifycss';


import webpack from 'webpack';
import sveltePlugin from 'esbuild-svelte';
import { vue3Plugin } from 'esbuild-plugin-vue-iii';

import config from './gulpfile_config.js';

import webpackConfigDev from './webpack.config.cjs';
import webpackConfigProd from './webpack.config.prod.cjs';

const gulpEsbuild = createGulpEsbuild({ piping: true });
const browser = browserSync.create();
const LOCALHOST_PROJECT_URL = process.env.LOCAL_SITE_URL;
const shouldMinify =  process.env.NODE_ENV === 'production';
const cssOutputStyle = shouldMinify ? 'compressed' : 'expanded';



// TODO: Move to a separate file
const config_webpack_js_admin_blocks = {
  watch: [
    './src/core/framework-logic/blocks.js',
    './src/core/framework-logic/register_block.js',
    './src/blocks/**/*.js',
    '!./src/blocks/**/frontend.js', // Exclude frontend.js files
    '!./src/blocks/**/frontend-js/*.js', // Exclude frontend.js files
    '!./src/blocks/**/frontend-js/**/*.js', // Exclude frontend.js files
    './src/blocks_shared_css_and_js/**/*.js',
    './src/core/blocks_array.json',
    './src/core/framework-logic/components/**/*.js',
    './src/core/framework-logic/helpers/**/*.js',
    './src/blocks/**/model.json',
    './src/blocks/**/EditMain.js',
    './src/blocks/**/EditSidebar.js',
    './src/blocks/**/View.js'
  ]
}

const webpackConfig =
  process.env.NODE_ENV === 'production'
    ? webpackConfigDev
    : webpackConfigProd

const autoprefixerConfig = {
  cascade: true,
  remove: true,
  grid: false,
  flexbox: false,
};



function compileSCSS(key) {
  function task(done) {
    const cssConfig = config.css[key];

    gulp.src(cssConfig.src)
      .pipe(sourcemaps.init())
      .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
      .pipe(sass({ outputStyle: cssOutputStyle }).on('error', sass.logError))
      .pipe(autoprefixer(autoprefixerConfig))
      .pipe(gulpif(shouldMinify, gcmq()))
      .pipe(gulpif(shouldMinify, minifycss()))
      .pipe(gulpif(cssConfig.hasOwnProperty('rename'), rename(cssConfig.rename)))
      .pipe(sourcemaps.write(cssConfig.sourcemaps))
      .pipe(size({ showFiles: true, showTotal: false, pretty: true, title: 'CSS size' }))
      .pipe(gulp.dest(cssConfig.dest))
      .pipe(browser.stream())
      .on('finish', done);
    };


    task.displayName = `compileSCSS:${key}`; // Set the display name
    return task
  }



function compileJS(key) {
  function task(done) {
    const jsConfig = config.js[key];

    const esbuild_obj = {
      bundle: true,
      minify: shouldMinify,
      logLevel: 'info',
      loader: { '.js': 'jsx' },
      plugins: [sveltePlugin(), vue3Plugin()]
    };

    if (jsConfig.hasOwnProperty('outfile')) {
      esbuild_obj.outfile = jsConfig.outfile;
    }

    if (jsConfig.hasOwnProperty('outdir')) {
      esbuild_obj.outdir = jsConfig.outdir;
    }

    gulp.src(jsConfig.src)
      .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
      .pipe(gulpEsbuild(esbuild_obj))
      .pipe(gulpif(jsConfig.hasOwnProperty('rename'), rename(jsConfig.rename)))
      .pipe(gulp.dest(jsConfig.dest))
      .pipe(browser.stream())
      .on('finish', done);
  }

  task.displayName = `compileJS:${key}`; // Set the display name
  return task;
}

// run webpack to compile Gutenberg backend JS
// TODO: Fix this fn not working
function compileGutenbergBackendJS(done) {
  return new Promise((resolve, reject) => {
    webpack(webpackConfig, (err, stats) => {
      if (err) {
        return reject(err)
      }

      if (stats.hasErrors()) {
        return reject(new Error(stats))
      }
      resolve()
      done()
    })
  })
}

function copyGutenbergBlocksFiles(done) {
  gulp
    .src('./src/blocks/**/model.json')
    .pipe(gulp.dest('./dist/block-specific'))
    .on('end', done); // Ensure the task signals completion
}

function serve() {
  browser.init({
    proxy: LOCALHOST_PROJECT_URL,
    injectChanges: true,
  });

    // Block-specific model.json copy
  gulp.watch(
    './src/blocks/**/model.json',
    gulp.series(copyGutenbergBlocksFiles)
  )

  // Watch for Gutenberg Backend Blocks JS (Handled via WebPack)
  gulp.watch(
    config_webpack_js_admin_blocks.watch,
    gulp.series(compileGutenbergBackendJS, browser.reload)
  )

  // Watch for CSS changes
 Object.keys(config.css).forEach((key) => {
    if (config.css[key].watch) {
      gulp.watch(config.css[key].watch, compileSCSS(key));
    }
  });


  // Watch for JS changes
  Object.keys(config.js).forEach((key) => {
    if (config.js[key].watch) {
      gulp.watch(config.js[key].watch, compileJS(key)).on('change', browser.reload);
    }
  });

  // Watch for PHP changes
  if (config?.latte?.watch) {
    gulp.watch(config.latte.watch).on('change', browser.reload);
  }
}
serve.displayName = 'serve';


async function build() {
  // Wait for all SCSS and JS tasks to complete
  await Promise.all([
    ...Object.keys(config.css).map(key => compileSCSS(key)),
    ...Object.keys(config.js).map(key => compileJS(key))
  ].map(task => new Promise((resolve, reject) => gulp.series(task)(err => {
    if (err) reject(err);
    else resolve();
  }))));
  
  // Compile Gutenberg blocks JS with Webpack
  await new Promise((resolve, reject) => {
    compileGutenbergBackendJS(err => {
      if (err) reject(err);
      else resolve();
    });
  });
}
build.displayName = 'build';


// Default task for development: compile, watch, and serve
function defaultTask() {
  serve();

  // Set up a parallel task for SCSS, JS, and Webpack compilation
  const tasks = [
    ...Object.keys(config.css).map(key => compileSCSS(key)),
    ...Object.keys(config.js).map(key => compileJS(key)),
    compileGutenbergBackendJS
  ];

  return gulp.parallel(tasks)();
}
defaultTask.displayName = 'default';


const compileAllSCSS = gulp.parallel(
  ...Object.keys(config.css).map((key) => compileSCSS(key))
);
compileAllSCSS.displayName = 'compileAllSCSS';

const compileAllJS = gulp.parallel(
  ...Object.keys(config.js).map((key) => compileJS(key))
);
compileAllJS.displayName = 'compileAllJS';

export { build, defaultTask as default };

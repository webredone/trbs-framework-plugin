const OUT_DIR = './dist'

const config = {
  css: {
    admin_blocks: {
      src: './src/core/framework-logic/scss/blocks-backend.scss',
      dest: `${OUT_DIR}/global_admin`,
      watch: [
        './src/core/framework-logic/scss/blocks-backend.scss',
        './src/core/framework-logic/scss/**/*.scss',
        './src/blocks/**/_editor.scss'
      ],
      sourcemaps: './css-maps'
    },
    fe_blocks_shared: {
      src: './src/blocks_shared_css_and_js/css/*.scss',
      dest: `${OUT_DIR}/blocks-shared`,
      watch: ['./src/blocks_shared_css_and_js/css/*.scss'],
      sourcemaps: './css-maps',
      rename: {
        suffix: '.min'
      }
    },
    fe_blocks_single: {
      src: './src/blocks/**/frontend.scss',
      dest: `${OUT_DIR}/block-specific`,
      watch: ['./src/blocks/**/frontend.scss'],
      sourcemaps: './css-maps',
      rename: {
        suffix: '.min'
      }
    }
  },

  js: {
    fe_blocks_shared: {
      src: './src/blocks_shared_css_and_js/js/*.js',
      dest: './',
      watch: ['./src/blocks_shared_css_and_js/js/*.js'],
      outdir: `${OUT_DIR}/blocks-shared`,
      rename: {
        suffix: '.min'
      }
    },
    fe_blocks_single: {
      src: './src/blocks/**/frontend.js',
      dest: './',
      watch: ['./src/blocks/**/frontend.js'],
      outdir: `${OUT_DIR}/block-specific`,
      rename: {
        suffix: '.min'
      }
    }
  },

  latte: {
    watch: './**/*.latte'
  }
}


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

// export default config

const compilation_config = {
  config,
  config_webpack_js_admin_blocks
}

export default compilation_config
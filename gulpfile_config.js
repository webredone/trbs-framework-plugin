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


export default config
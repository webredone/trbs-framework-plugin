// const path = require('path')
// const TerserPlugin = require('terser-webpack-plugin') 
// const outputDir = path.resolve(__dirname, 'dist', 'global_admin')

import path from 'path';
import url from 'url';
import TerserPlugin from 'terser-webpack-plugin';

const __dirname = path.dirname(url.fileURLToPath(import.meta.url));
const outputDir = path.resolve(__dirname, 'dist', 'global_admin');

const webpackProdConfig ={
  mode: 'production',
  entry: {
    blocks: path.resolve(__dirname, './src/blocks.js')
  },
  output: {
    path: outputDir,
    filename: '[name].min.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: ['babel-loader']
      }
    ]
  },
  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        parallel: true,
        terserOptions: {
          ecma: 6,
          format: {
            comments: false
          }
        },
        extractComments: false
      })
    ]
  }
}


export default webpackProdConfig;
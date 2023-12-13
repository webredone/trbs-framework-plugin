const path = require('path')
const outputDir = path.resolve(__dirname, 'dist', 'global_admin')

const webpackDevConfig = {
  mode: 'development',
  entry: {
    blocks: path.resolve(__dirname, './src/blocks.js')
  },
  devtool: 'inline-source-map',
  output: {
    path: outputDir,
    filename: '[name].min.js'
  },
  devtool: 'source-map',
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: ['babel-loader']
      },
      {
        test: /\.js$/,
        use: ['source-map-loader'],
        enforce: 'pre'
      }
    ]
  }
}

module.exports = webpackDevConfig

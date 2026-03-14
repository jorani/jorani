const path = require('path');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ASSET_PATH = './';

module.exports = {
  entry: {
    legacy: './src/legacy.js',
    modern: './src/modern.js',
    requirements: './src/requirements.js',
    swagger: './src/swagger.js',
  },
  output: {
    path: path.resolve(__dirname, 'assets/dist'),
    publicPath: ASSET_PATH,
    library: 'jorani',
    libraryTarget: 'var',
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader',
        ],
      },
      {
        test: /\.css$/,
        use: [
          { loader: 'style-loader' },
          { loader: 'css-loader' },
        ]
      },
      {
        test: /\.(png|svg|jpg|gif)$/,
        use: [
          'file-loader',
        ],
      },
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/,
        use: [
          'file-loader',
        ],
      },
    ],
  },
  plugins: [
    // Strip all moment's locales except those supported by Jorani
    new MomentLocalesPlugin({
        localesToKeep: ['en','en-gb','fr','es','nl','de','it','ru','cs','uk','km','fa','vi','tr','zh-cn','el','pt','ar','hu','ca','ro','sk'],
    }),
    new MiniCssExtractPlugin({
      filename: '[name].css',
      chunkFilename: '[id].css',
    }),
  ],
};

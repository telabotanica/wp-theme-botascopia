// const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin')

// change these variables to fit your project
const jsPath= './assets/scripts';
const cssPath = './';
const outputPath = 'dist';

const extractBundle = new ExtractTextPlugin('bundle.css');
const entryPoints = {
  // 'app' is the output name, people commonly use 'bundle'
  // you can have more than 1 entry point
  'app': jsPath + '/main.js',

};

const PATHS = {
  assets: {
    fonts: path.join(__dirname, 'assets', 'fonts'),
    icons: path.join(__dirname, 'assets', 'icons'),
  },
  modules: path.join(__dirname, 'modules'),
  dist: path.join(__dirname, 'dist'),
};

module.exports = {
  devtool: 'source-map',
  entry: entryPoints,
  output: {
    path: PATHS.dist,
    // filename: '[name].js',
    filename: 'bundle.js',
  },

  plugins: [
    extractBundle
    // new MiniCssExtractPlugin({
    //   filename: '[name].css',
    // }),

    // Uncomment this if you want to use CSS Live reload
    /*
    new BrowserSyncPlugin({
      proxy: localDomain,
      files: [ outputPath + '/*.css' ],
      injectCss: true,
    }, { reload: false, }),
    */
  ],
  module: {
    rules: [
      {
        test: /\.s?[c]ss$/i,
        use: extractBundle.extract({
          fallback: 'style-loader',
          use: [ 'css-loader']
        })
      },
      {
        test: /main\.css$/,
        use: extractBundle.extract({
          fallback: 'style-loader',
          use: [ 'css-loader']
        })
      },
      {
        test: /\.(jpg|jpeg|png|gif|woff|woff2|eot|ttf)$/i,
        use: 'url-loader?limit=1024',
      },
      {
        test: /\.pug$/,
        include: [
          PATHS.modules
        ],
        use: 'pug-loader'
      },
      {
        test: /\.svg$/,
        include: [
          PATHS.assets.icons
        ],
        use: 'svg-sprite-loader?name=icon-[name]'
      },
      {
        test: /\.svg$/,
        exclude: [
          PATHS.assets.fonts,
          PATHS.assets.icons,
        ],
        use: 'svg-url-loader'
      },
    ]
  },
  externals: {
    // require("jquery") is external and available
    //  on the global var jQuery
    "jquery": "jQuery"
  }
};
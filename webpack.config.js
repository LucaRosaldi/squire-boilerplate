/**
 *
 * Webpack configuration.
 *
 * This is a minimal configuration, which compiles, bundles and
 * minifies scripts and styles (with source maps). Also includes
 * a dev server with the Browser Sync plugin for Webpack, which
 * automatically runs with the --watch flag.
 *
 * commands:
 * 'npm run build' -> compiles everything for production.
 * 'npm run watch' -> starts a live dev server.
 *
 */
var config = require( './config.json' );
var webpack = require( 'webpack' );
var path = require( 'path' );
var ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
var BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

// browsersync config
var bsConfig = {
  host: config.server.host || 'localhost',
  port: config.server.port || 3000,
}
if ( config.server.proxy ) {
  bsConfig.proxy = config.server.proxy;
} else {
  bsConfig.server = { baseDir: [ config.root.dist ] };
}

// webpack config
var webpackConfig = {
  context: path.resolve( __dirname, config.root.src ),
  entry: [
    '.' + path.sep + config.js.src,
    '.' + path.sep + config.css.src
  ],
  output: {
    filename: config.js.dist,
    path: path.resolve( __dirname, config.root.dist ),
    publicPath: '/'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        loaders: [
          'babel-loader'
        ],
        exclude: /node_modules/
      },
      {
        test: /\.scss$/,
        loader: ExtractTextPlugin.extract([
          'css-loader?importLoaders=1&sourceMap=1&minimize=1',
          'sass-loader?sourceMap=1'
        ])
      }
    ]
  },
  devtool: 'source-map',
  plugins: [
    new webpack.optimize.UglifyJsPlugin({ minimize: true, sourceMap: true }),
    new ExtractTextPlugin( config.css.dist ),
    new BrowserSyncPlugin( bsConfig )
  ]
};

module.exports = webpackConfig;

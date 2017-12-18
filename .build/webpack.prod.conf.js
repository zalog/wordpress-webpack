const webpack = require('webpack'),
  uglifyJSPlugin = require('uglifyjs-webpack-plugin');



const config = {
  plugins: [
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify('production')
    }),
    new uglifyJSPlugin(),
    new webpack.optimize.ModuleConcatenationPlugin()
  ]
};

module.exports = config;

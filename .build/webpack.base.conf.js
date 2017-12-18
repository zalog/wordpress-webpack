const webpack = require('webpack'),

  packageJson = require('./../package.json');



const config = {
  output: {
    publicPath: '/wp-content/themes/' + packageJson.name + '/assets/js/',
    filename: '[name].js',
    chunkFilename: '[name]-chunk.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              ["@babel/preset-env"]
            ],
            plugins: ["lodash", "@babel/plugin-syntax-dynamic-import"]
          }
        }
      }
    ]
  },
  plugins: [
    new webpack.optimize.CommonsChunkPlugin({
      name: ["commons"],
      filename: "commons.js"
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      Popper: ['popper.js', 'default'],
      Util: "exports-loader?Util!bootstrap/js/dist/util"
    })
  ],
  resolve: {
    alias: {
      'jquery': "jquery/dist/jquery.slim"
    }
  }
};

module.exports = config;

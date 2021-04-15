const path = require('path');
const webpack = require('webpack');
const css = require("./src/css/main.css").toString();

module.exports = {
  mode:'development',
  devServer: {
    port: 9001,
    headers: { "Access-Control-Allow-Origin": "*" },
    contentBase: path.join(__dirname, '/')
  },
  entry: {
    main: './src/js/main.js',
    login: './src/js/login',
  },
  output: {
    path: path.resolve(__dirname, 'build'),
    filename: '[name].js',
  }
};
 var path = require('path');
 var webpack = require('webpack');
 var vendors = require('./public/assets/js/vendors/index.js');

 module.exports = {
     entry: {
        app : './public/assets/js/app/index.js',
        vendor : vendors,
     },
     output: {
         path: path.resolve(__dirname, 'public/dist/js/'),
         filename: '[name].bundle.js'
     },
     module: {
         loaders: [
             {  
                 test : /\.js$/,
                 include : [
                    path.resolve(__dirname, 'public/assets/js/app/'),
                 ],  
                 loader: 'babel-loader',
                 query: {
                     presets: ['es2015']
                 }
             }
         ]
     },
     plugins : [
        new webpack.optimize.CommonsChunkPlugin({
            name : 'vendor',
            filename : 'vendors.bundle.js',
        }),
     ],
     stats: {
         colors: true
     },
 };
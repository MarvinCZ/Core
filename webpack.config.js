const webpack = require('webpack')
const path = require('path')
var ExtractTextPlugin = require('extract-text-webpack-plugin');

const config = {
    context: path.resolve(__dirname, 'assets'),
    entry: ['./app.js', './app.scss'],
    output: {
        path: path.resolve(__dirname, 'public/assets'),
        filename: 'bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                include: path.resolve(__dirname, 'assets'),
                use: [{
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            ['es2015', { modules: false }]
                        ]
                    }
                }]
            },
            { // sass / scss loader for webpack
                test: /\.(sass|scss)$/,
                loader: ExtractTextPlugin.extract(['css-loader', 'sass-loader'])
            },
            {
                test: /\.(ttf|eot|woff|woff2|svg)$/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        outputPath: '../fonts/'
                    }
                }]
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin({ // define where to save the file
            filename: '[name].css',
            allChunks: true
        }),
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
            Tether: 'tether',
            Popper: ['popper.js', 'default'],
            "window.jQuery": "jquery",
            "window.Tether": 'tether'
        })
    ]
}

module.exports = config

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

module.exports = [
    // Block build configuration
    {
        ...defaultConfig,
        entry: {
            'block': './block/index.js',
        },
        output: {
            path: path.resolve(__dirname, 'build/block'),
            filename: '[name].js',
        },
        plugins: [
            ...defaultConfig.plugins,
            new MiniCssExtractPlugin({
                filename: '[name].css'
            })
        ],
        optimization: {
            minimize: true,
            minimizer: [
                new TerserPlugin(),
                new CssMinimizerPlugin()
            ]
        }
    },
    // JS only build configuration
    {
        entry: {
            'dappier-settings-js': './src/js/dappier-settings.js'
        },
        output: {
            path: path.resolve(__dirname, 'build'),
            filename: 'js/dappier-settings.js',
            clean: false
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
                new TerserPlugin()
            ]
        }
    },
    // CSS only build configuration
    {
        entry: {
            'dappier-settings-css': './src/css/dappier-settings.css'
        },
        output: {
            path: path.resolve(__dirname, 'build'),
            clean: false
        },
        module: {
            rules: [
                {
                    test: /\.css$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader'
                    ]
                }
            ]
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'css/dappier-settings.css'
            })
        ],
        optimization: {
            minimize: true,
            minimizer: [
                new CssMinimizerPlugin()
            ]
        }
    }
];
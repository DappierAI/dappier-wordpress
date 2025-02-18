const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

module.exports = {
    ...defaultConfig,
    entry: {
        'js/dappier-settings': './src/js/dappier-settings.js',
        'css/dappier-settings': './src/css/dappier-settings.css',
        'block/block': './block/index.js',
        'block/editor': './block/editor.css'
    },
    plugins: [
        ...defaultConfig.plugins,
        new RemoveEmptyScriptsPlugin()
    ]
};
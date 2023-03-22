const path = require('path');

module.exports = {
    watch: true,
    watchOptions: {
        ignored: /node_modules/,
    },
    mode: 'development',
    entry: './public/js/main.js',
    output: {
        filename: 'bundled.js',
        path: path.resolve(__dirname, 'public/dist'),
    },
};

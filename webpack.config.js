const path = require('path');

module.exports = {
    mode: 'development',
    entry: './public/js/main.js',
    output: {
        filename: 'bundled.js',
        path: path.resolve(__dirname, 'public/dist'),
    },
};

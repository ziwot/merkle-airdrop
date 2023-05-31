const mix = require('laravel-mix');

mix.setPublicPath('./webroot')
    .js('assets/js/app.js', 'webroot/js')
    .css('assets/css/app.css', 'webroot/css')
    .webpackConfig({
        resolve: {
            fallback: {
                "stream": require.resolve("stream-browserify"),
                "fs": false
            }
        }
    })
    .browserSync({
        proxy: 'localhost:8765'
    })
    .version();

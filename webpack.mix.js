const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/scss/app.scss', 'public/css')
    .sass('resources/scss/header.scss', 'public/css');

mix.browserSync({
    proxy: 'http://milam.lh',
    files: [
        'public/dev/*.html',
        'app/**/*.php',
        'resources/views/**/*.php',
        'public/js/**/*.js',
        'public/css/**/*.css',
        'resources/scss/**/*.scss'
    ]
});

if (mix.inProduction()){
    mix.version();
}

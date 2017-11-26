let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');


// mix.styles([
//     'resources/assets/css/bootstrap.min.css',
//     'resources/assets/css/font-awesome.min.css',
//     'resources/assets/css/stylesheet.css',
//     'resources/assets/css/pnnotify.css',
//     'resources/assets/css/select2.css',
//     'resources/assets/css/main.css',
//     'resources/assets/css/owl.carousel.css',
//     'resources/assets/css/owl.theme.default.css'
// ], 'public/css/app.css');

mix.js('resources/assets/js/app.js', 'public/js');
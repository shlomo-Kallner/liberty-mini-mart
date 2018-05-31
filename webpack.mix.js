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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.scripts(['html5shiv', 'respond.js', 'selectivizr'], 'public/js/compatibility.js');

mix.scripts([] , 'public/js/support.js');

mix.scripts(
    [
        'lib/themewagon/metronicShopUI/theme/assets/corporate/scripts/back-to-top.js',
        'lib/themewagon/metronicShopUI/theme/assets/corporate/scripts/layout.js',
        'lib/themewagon/metronicShopUI/theme/assets/pages/scripts/bs-carousel.js',

    ] , 'public/js/metronic.js');
mix.scripts(
    [
        'lib/bootstrapious/universal/js/front.js'
    ] , 'public/js/bootstrapious.js');
mix.scripts([] , 'public/js/bootstrapmade.js');
//mix.scripts([] , 'public/js/compatibility.js');

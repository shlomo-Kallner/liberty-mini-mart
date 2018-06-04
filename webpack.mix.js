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

//mix.scripts([],'public/css/.css');
mix.scripts([],'public/css/support.css');
mix.scripts([],'public/css/metronic.css');
mix.scripts([],'public/css/bootstrapious.css');
mix.scripts(['public/lib/bootstrapmade/flexor/css/style.css'],'public/css/bootstrapmade.css');

mix.scripts(['html5shiv', 'respond.js', 'selectivizr'], 'public/js/compatibility.js');

mix.scripts(
    [
        'public/lib/require.js/require.min.js',
        'public/lib/history.js/scripts/bundled/html4+html5/jquery.history.js',
        'public/lib/themewagon/metronicShopUI/theme/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        // jquery, font-awesome, bootstrap 3.x, OwlCarousel(?), fancybox, ...
        'public/lib/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js'
    ] , 'public/js/support.js');

mix.scripts(
    [
        'public/lib/themewagon/metronicShopUI/theme/assets/corporate/scripts/back-to-top.js',
        'public/lib/themewagon/metronicShopUI/theme/assets/corporate/scripts/layout.js',
        'public/lib/themewagon/metronicShopUI/theme/assets/pages/scripts/bs-carousel.js',

    ] , 'public/js/metronic.js');
mix.scripts(
    [
        'public/lib/bootstrapious/universal/js/front.js'
    ] , 'public/js/bootstrapious.js');
mix.scripts([] , 'public/js/bootstrapmade.js');
//mix.scripts([] , 'public/js/compatibility.js');

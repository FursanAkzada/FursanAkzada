const mix = require('laravel-mix');

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

/* Apps */
mix.js('resources/js/base/index.js', 'public/assets/js/base.bundle.js');
mix.js('resources/js/modules/index.js', 'public/assets/js/modules.bundle.js');
mix.js('resources/js/theme.js', 'public/assets/js/theme.bundle.js');
mix.js('resources/js/app.js', 'public/assets/js');

mix.sass('resources/sass/style.scss', 'public/assets/css/style.bundle.css').options({
    processCssUrls: false
});
// mix.sass('resources/scss/themes/layout/theme-layout.scss', 'public/assets/css/theme-layout.bundle.css').options({
//     processCssUrls: false
// });
// mix.sass('resources/scss/themes/helpers.scss', 'public/assets/css/helpers.bundle.css').options({
//     processCssUrls: false
// });

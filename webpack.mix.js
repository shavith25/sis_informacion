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

// 1. Compila el JS principal y extrae las librerías de node_modules.
//    Esto generará 3 archivos clave: app.js, vendor.js y manifest.js.
mix.js('resources/js/app.js', 'public/js')
   .extract([
       'jquery',
       'popper.js',
       'bootstrap',
       'sweetalert',
       'izitoast',
       'select2',
       'jquery.nicescroll',
       'moment',
       'leaflet-image'
   ]);

// 2. Combina y compila todos los archivos CSS en un único app.css.
//    Es más eficiente que usar @import en un CSS.
mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.min.css',
    'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
    'node_modules/izitoast/dist/css/iziToast.min.css',
    'node_modules/sweetalert/dist/sweetalert.css',
    'node_modules/select2/dist/css/select2.min.css',
    'public/web/css/style.css',
    'public/web/css/components.css',
    'public/css/ayuda.css'
], 'public/css/app.css');

// 3. Compila otros archivos JS específicos que necesites por separado.
mix.js('resources/assets/js/profile.js', 'public/assets/js/profile.js');
mix.js('resources/assets/js/custom/custom.js', 'public/assets/js/custom/custom.js');
mix.js('resources/assets/js/custom/custom-datatable.js', 'public/assets/js/custom/custom-datatable.js');

// 4. Activa el versionamiento para evitar problemas de caché en producción.
if (mix.inProduction()) {
    mix.version();
}

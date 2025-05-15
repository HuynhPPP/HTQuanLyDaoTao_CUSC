let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
.js('resources/js/toast.js', 'public/js')
.js('resources/js/validation.js', 'public/js')
.sass('resources/scss/app.scss', 'public/css', [

]);
const mix = require('laravel-mix');

mix.js('resources/app.js', 'public/js')
   .sass('resources/app.scss', 'public/css');
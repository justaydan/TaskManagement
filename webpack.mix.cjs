const mix = require('laravel-mix');

// Basic CSS
mix.css('resources/css/app.css', 'public/css');

// SASS example
mix.sass('resources/sass/app.scss', 'public/css');

// JavaScript
mix.js('resources/js/app.js', 'public/js');

// Enable versioning for cache busting
mix.version();

let mix = require('laravel-mix');
const path = require("path");

mix.setResourceRoot('../');
mix.setPublicPath('../');

mix
    .js('assets/map-styles.js', './js/map-styles.js')
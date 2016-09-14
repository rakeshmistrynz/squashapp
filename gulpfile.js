var elixir = require('laravel-elixir');

/*
 |----------------------------------------------------------------
 | Have a Drink!
 |----------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic
 | Gulp tasks for your Laravel application. Elixir supports
 | several common CSS, JavaScript and even testing tools!
 |
 */

var paths = {
	'jquery': './resources/assets/bower/jquery/',
	'bootstrap': './resources/assets/bower/bootstrap-sass-official/assets/',
	'bootstrap_datepicker':'./resources/assets/bower/bootstrap-datepicker/dist/',
	'typeahead':'./resources/assets/bower/typeahead.js/dist/',
	'fontawesome':'./resources/assets/bower/fontawesome/',
	'webfonts':'./resources/assets/webfonts/',
	'd3':'./resources/assets/bower/d3/',
	'js':'./resources/assets/js/',
	'images':'./resources/assets/images/'
};

elixir(function(mix) {
    mix.sass("style.scss", 'public/css/', {includePaths: [paths.bootstrap + 'stylesheets/', paths.fontawesome + 'scss/']})
        .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts/bootstrap/')
        .copy(paths.fontawesome + 'fonts/**', 'public/fonts/fontawesome/')
        .copy(paths.webfonts + '**', 'public/fonts/webfonts/')
        .copy(paths.bootstrap_datepicker + 'css/bootstrap-datepicker3.css','public/css/vendor/')
        .copy(paths.images + '**','public/images/')
        .scripts([
            paths.jquery + "dist/jquery.js",
            paths.bootstrap + "javascripts/bootstrap.js",
            paths.bootstrap_datepicker + "js/bootstrap-datepicker.js",
            paths.typeahead + "typeahead.bundle.js",
            paths.d3 + "d3.js",
            paths.js + "js.js"
        ], 'public/js/app.js', './')
        .styles([
		"style.css"], null,'public/css')
		.version('public/css/all.css');
});



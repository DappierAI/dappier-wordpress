const mix     = require('laravel-mix');
const glob    = require('glob');
const path    = require('path');
const cssnano = require('cssnano');

// Process all CSS files in /src/css/ and output only minified versions to /build/css/
glob.sync('src/css/!(*.min).css').forEach(file => {
	const fileName = path.basename(file, '.css');

	// Create a minified version directly
	mix.postCss(file, `build/css/${fileName}.min.css`, [
		cssnano({
			preset: 'default',
		})
	]);
});

// Process all JS files in /src/js/ and output only minified versions to /build/js/
glob.sync('src/js/!(*.min).js').forEach(file => {
	const fileName = path.basename(file, '.js');

	// Minify the JS file and output as .min.js
	mix.js(file, `build/js/${fileName}.min.js`);
});
/* eslint-env node */

const gulp = require( 'gulp' );

const cleanCSS = require( 'gulp-clean-css' );
const composer = require( 'gulp-uglify/composer' );
const concat = require( 'gulp-concat' );
const inject = require( 'gulp-inject-string' );
const merge = require( 'merge-stream' );
const rename = require( 'gulp-rename' );
const ts = require( 'gulp-typescript' );
const uglify = require( 'uglify-js' );

const minify = composer( uglify, console );

gulp.task( 'build:css:main', function() {
	return gulp.src( 'src/css/style.css' )
		.pipe( gulp.dest( 'dist/' ) );
} );

gulp.task( 'build:css:admin', function() {
	return gulp.src( 'src/css/admin/**/*.css' )
		.pipe( cleanCSS( { compatibility: 'ie8' } ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulp.dest( 'dist/admin/css/' ) );
} );

gulp.task( 'build:css', gulp.parallel( 'build:css:main', 'build:css:admin' ) );

function bundle( name: string, sources: Array<string>, part: string, jQuery = false ): void {
	const tsProject = ts.createProject( 'tsconfig.json' );
	let ret = gulp.src( sources.concat( [ 'src/d.ts/**/*.d.ts' ] ) )
		.pipe( tsProject() )
		.js
		.pipe( concat( name + '.min.js' ) );
	if ( jQuery ) {
		ret = ret.pipe( inject.prepend( 'jQuery( document ).ready( function( $ ) {\n' ) )
			.pipe( inject.append( '} );\n' ) );
	}
	return ret.pipe( minify( { ie8: true } ) )
		.pipe( gulp.dest( 'dist/' + part + '/js/' ) );
}

gulp.task( 'build:js', function() {
	return merge(
		bundle( 'preset_customize_control', [ 'src/ts/admin/preset_customize_control.ts' ], 'admin', true )
	);
} );

gulp.task( 'build:php:functions', function() {
	return gulp.src( 'src/php/functions.php' )
		.pipe( gulp.dest( 'dist/' ) );
} );

gulp.task( 'build:php:admin', function() {
	return gulp.src( 'src/php/admin/**/*.php' )
		.pipe( gulp.dest( 'dist/admin/' ) );
} );

gulp.task( 'build:php', gulp.parallel( 'build:php:functions', 'build:php:admin' ) );

gulp.task( 'build:png:screenshot', function() {
	return gulp.src( 'src/png/screenshot.png' )
		.pipe( gulp.dest( 'dist/' ) );
} );

gulp.task( 'build:png:admin', function() {
	return gulp.src( 'src/png/admin/**/*.png' )
		.pipe( gulp.dest( 'dist/admin/' ) );
} );

gulp.task( 'build:png', gulp.parallel( 'build:png:screenshot', 'build:png:admin' ) );

gulp.task( 'build:txt', function() {
	return gulp.src( [ 'src/txt/license.txt', 'src/txt/readme.txt' ] )
		.pipe( gulp.dest( 'dist/' ) );
} );

gulp.task( 'build', gulp.parallel( 'build:css', 'build:js', 'build:php', 'build:png', 'build:txt' ) );

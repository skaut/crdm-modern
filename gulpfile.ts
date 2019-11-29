/* eslint-env node */

const gulp = require( 'gulp' );

const composer = require( 'gulp-uglify/composer' );
const concat = require( 'gulp-concat' );
const inject = require( 'gulp-inject-string' );
const merge = require( 'merge-stream' );
const ts = require( 'gulp-typescript' );
const uglify = require( 'uglify-js' );

const minify = composer( uglify, console );

gulp.task( 'build:assets', function() {
	return gulp.src( [ 'src/assets/license.txt', 'src/assets/readme.txt', 'src/assets/style.css' ] )
		.pipe( gulp.dest( 'dist/' ) );
} );

function bundle( name: string, sources: Array<string>, part: string, jQuery = false ): void {
	const tsProject = ts.createProject( 'tsconfig.json' );
	let ret = gulp.src( sources.concat( [ 'src/d.ts/*.d.ts' ] ) )
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

gulp.task( 'build:png', gulp.parallel( 'build:png:screenshot' ) );

gulp.task( 'build', gulp.parallel( 'build:assets', 'build:js', 'build:php', 'build:png' ) );

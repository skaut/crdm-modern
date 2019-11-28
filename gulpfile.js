/* eslint-env node */

const gulp = require( 'gulp' );

gulp.task( 'build:assets', function() {
	return gulp.src( [ 'src/assets/license.txt', 'src/assets/readme.txt', 'src/assets/screenshot.png', 'src/assets/style.css' ] )
		.pipe( gulp.dest( 'dist/' ) );
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

gulp.task( 'build', gulp.parallel( 'build:assets', 'build:php' ) );

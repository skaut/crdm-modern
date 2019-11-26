/* eslint-env node */

const gulp = require( 'gulp' );

gulp.task( 'build:assets', function() {
	return gulp.src( [ 'src/assets/license.txt', 'src/assets/readme.txt', 'src/assets/screenshot.png', 'src/assets/style.css' ] )
		.pipe( gulp.dest( 'dist/' ) );
} );

gulp.task( 'build', gulp.parallel( 'build:assets' ) );

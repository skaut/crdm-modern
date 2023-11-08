/* eslint-env node */

const gulp = require('gulp');

const cleanCSS = require('gulp-clean-css');
const concat = require('gulp-concat');
const inject = require('gulp-inject-string');
const merge = require('merge-stream');
const rename = require('gulp-rename');
const terser = require('gulp-terser');
const ts = require('gulp-typescript');
const exec = require('child_process').exec;

gulp.task('build:css:main', () =>
	gulp
		.src([
			'src/css/style.css',
			'src/css/frontend/blog.css',
			'src/css/frontend/footer.css',
			'src/css/frontend/header-image.css',
			'src/css/frontend/sidebar.css',
			'src/css/frontend/site-title.css',
			'src/css/frontend/title-widget.css',
		])
		.pipe(cleanCSS({ compatibility: 'ie8' }))
		.pipe(concat('style.css'))
		.pipe(gulp.dest('dist/'))
);

gulp.task('build:css:admin', () =>
	gulp
		.src('src/css/admin/**/*.css')
		.pipe(cleanCSS({ compatibility: 'ie8' }))
		.pipe(rename({ suffix: '.min' }))
		.pipe(gulp.dest('dist/admin/css/'))
);

gulp.task('build:css', gulp.parallel('build:css:main', 'build:css:admin'));

gulp.task('build:deps:npm:dripicons', () =>
	gulp
		.src(
			[
				'node_modules/dripicons/webfont/webfont.css',
				'node_modules/dripicons/webfont/fonts/*',
			],
			{ base: 'node_modules/dripicons/webfont' }
		)
		.pipe(gulp.dest('dist/frontend/dripicons'))
);

gulp.task('build:deps:npm', gulp.parallel('build:deps:npm:dripicons'));

gulp.task('build:deps', gulp.parallel('build:deps:npm'));

gulp.task('build:jpg:screenshot', () =>
	gulp.src('src/jpg/screenshot.jpg').pipe(gulp.dest('dist/'))
);

gulp.task('build:jpg:frontend', () =>
	gulp
		.src('src/jpg/frontend/**/*.jpg')
		.pipe(gulp.dest('dist/frontend/images/'))
);

gulp.task('build:jpg:admin', () =>
	gulp.src('src/jpg/admin/**/*.jpg').pipe(gulp.dest('dist/admin/'))
);

gulp.task(
	'build:jpg',
	gulp.parallel(
		'build:jpg:screenshot',
		'build:jpg:frontend',
		'build:jpg:admin'
	)
);

function bundle(name, sources, part, jQuery = false) {
	const tsProject = ts.createProject('tsconfig.json');
	let ret = gulp
		.src(sources.concat(['src/d.ts/**/*.d.ts']))
		.pipe(tsProject())
		.js.pipe(concat(name + '.min.js'));
	if (jQuery) {
		ret = ret
			.pipe(inject.prepend('jQuery(document).ready( function( $) {\n'))
			.pipe(inject.append('});\n'));
	}
	return ret
		.pipe(terser({ ie8: true }))
		.pipe(gulp.dest('dist/' + part + '/js/'));
}

gulp.task('build:js', () =>
	merge(
		bundle(
			'preset_customize_control',
			['src/ts/admin/preset_customize_control.ts'],
			'admin',
			true
		),
		bundle(
			'preset_on_activation',
			['src/ts/admin/preset_on_activation.ts'],
			'admin',
			true
		),
		bundle(
			'customizer',
			[
				'src/ts/admin/customizer.ts',
				'src/ts/admin/liveReload.ts',
				'src/ts/admin/LiveReloadComputedProperty.ts',
				'src/ts/admin/LiveReloadMediaRules.ts',
				'src/ts/admin/LiveReloadProperty.ts',
				'src/ts/admin/LiveReloadTarget.ts',
			],
			'admin',
			true
		),
		bundle('blog', ['src/ts/frontend/blog.ts'], 'frontend', true)
	)
);

gulp.task(
	'build:mo',
	gulp.series(
		(cb) => {
			exec(
				'./vendor/bin/wp i18n make-mo src/languages/ dist/languages/ ',
				(err) => {
					cb(err);
				}
			);
		},
		() =>
			gulp
				.src('dist/languages/*.mo')
				.pipe(
					rename((path) => {
						path.basename = path.basename.substring(
							path.basename.lastIndexOf('-') + 1
						);
					})
				)
				.pipe(gulp.dest('dist/languages/')),
		(cb) => {
			exec('rm dist/languages/crdm-modern-*.mo', (err) => {
				cb(err);
			});
		}
	)
);

gulp.task('build:php:root', () =>
	gulp.src('src/php/*.php').pipe(gulp.dest('dist/'))
);

gulp.task('build:php:admin', () =>
	gulp.src('src/php/admin/**/*.php').pipe(gulp.dest('dist/admin/'))
);

gulp.task('build:php:frontend', () =>
	gulp.src('src/php/frontend/**/*.php').pipe(gulp.dest('dist/frontend/'))
);

gulp.task(
	'build:php',
	gulp.parallel('build:php:root', 'build:php:admin', 'build:php:frontend')
);

gulp.task('build:png:frontend', () =>
	gulp
		.src('src/png/frontend/**/*.png')
		.pipe(gulp.dest('dist/frontend/images/'))
);

gulp.task('build:png', gulp.parallel('build:png:frontend'));

gulp.task('build:txt', () =>
	gulp
		.src(['src/txt/license.txt', 'src/txt/readme.txt'])
		.pipe(gulp.dest('dist/'))
);

gulp.task(
	'build',
	gulp.parallel(
		'build:css',
		'build:deps',
		'build:jpg',
		'build:js',
		'build:mo',
		'build:php',
		'build:png',
		'build:txt'
	)
);

gulp.task('update-translations:generate-pot', (cb) => {
	exec(
		'./vendor/bin/wp i18n make-pot src/ src/languages/crdm-modern.pot --slug="crdm-modern" --headers=\'{"Report-Msgid-Bugs-To": "https://github.com/skaut/crdm-modern/issues", "POT-Creation-Date": "", "Last-Translator": "Marek Dědič <developer@dedic.eu>", "Language-Team": ""}\' --file-comment="Copyright (C) Junák – český skaut" --package-name="crdm-modern"',
		(err) => {
			cb(err);
		}
	);
});

gulp.task('update-translations:update-po', (cb) => {
	exec(
		'./vendor/bin/wp i18n update-po src/languages/crdm-modern.pot src/languages/',
		(err) => {
			cb(err);
		}
	);
});

gulp.task(
	'update-translations',
	gulp.series(
		'update-translations:generate-pot',
		'update-translations:update-po'
	)
);

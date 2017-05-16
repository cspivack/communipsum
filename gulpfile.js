var gulp = require('gulp'),
	sass = require('gulp-sass'),
	uglify = require('gulp-uglify'),
	browserSync = require('browser-sync').create(),
	watch = require('gulp-watch'),
	csso = require('gulp-csso'),
	autoprefixer = require('gulp-autoprefixer'),
	notify = require('gulp-notify'),
	plumber = require('gulp-plumber'),
	bourbon = require('node-bourbon').includePaths,
	neat = require('node-neat').includePaths,
	riot = require('gulp-riot'),
	jshint = require('gulp-jshint'),
	stylish = require('jshint-stylish'),
	runSequence = require('run-sequence'),
    footer = require('gulp-footer'),
    rename = require('gulp-rename'),
    fs = require('fs');

var paths = {
	scss: './resources/assets/scss/**/*.scss',
	riot: './resources/assets/riot/**/*.tag',
	js: './public/js',
	jsSrc: './resources/assets/js/app.js',
	css: './public/css'
};

var config = require('./config.json');

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: config.proxy,
        browser: 'chrome',
        tunnel: 'kjqmilomtr',
        files: ['**/*.php', './index.html']
    });
});

gulp.task('sass', function () {
	return gulp.src(paths.scss)
		.pipe(plumber({errorHandler: notify.onError({
			message: "<%= error.message %>",
	        title: "SASS error"
		})}))
		.pipe(sass({
		    includePaths: ['sass'].concat(neat)
		}))
		.pipe(autoprefixer({
		    browsers: ['> 1%'],
		    cascade: false
		}))
		.pipe(csso())
		.pipe(gulp.dest(paths.css))
		.pipe(browserSync.stream());
});

gulp.task('riot', function () {
    return gulp.src(paths.riot)
        .pipe(plumber({errorHandler: notify.onError("Riot compilation error: <%= error.message %>")}))
        .pipe(riot({
            compact: true
        }))
        .pipe(footer(fs.readFileSync(paths.jsSrc)))
        .pipe(rename('app.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.js))
		.pipe(browserSync.stream());
});

gulp.task('watch', function () {
	runSequence('browser-sync');
	gulp.watch(paths.scss, ['sass']);
	gulp.watch(paths.riot, ['riot']);
	gulp.watch(paths.jsSrc, ['riot']);
});

gulp.task('default', function() {
	runSequence('sass', 'riot');
});
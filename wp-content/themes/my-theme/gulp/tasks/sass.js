var gulp         = require('gulp');
var sass         = require('gulp-sass');
var rename       = require('gulp-rename');
var sourcemaps   = require('gulp-sourcemaps');
var postcss      = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var config       = require('../config');
var csso = require('postcss-csso');

var processors = [
    autoprefixer({
        cascade: false
    }),
    require('lost'),
    csso
];

gulp.task('sassFrontend', function() {
    return gulp
        .src(config.src.sassFrontend + '/*.{sass,scss}')
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: config.production ? 'compact' : 'expanded', // nested, expanded, compact, compressed
            precision: 5
        }))
        .on('error', config.errorHandler)
        .pipe(postcss(processors))
        .pipe(rename(`frontend.css`))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(config.dest.css));
});

gulp.task('sassFrontend:watch', function() {
    gulp.watch(config.src.sassFrontend + '/**/*.{sass,scss}', ['sassFrontend']);
});


gulp.task('sassBackend', function() {
    return gulp
        .src(config.src.sassBackend + '/*.{sass,scss}')
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: config.production ? 'compact' : 'expanded', // nested, expanded, compact, compressed
            precision: 5
        }))
        .on('error', config.errorHandler)
        .pipe(postcss(processors))
        .pipe(rename(`backend.css`))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(config.dest.css));
});

gulp.task('sassBackend:watch', function() {
    gulp.watch(config.src.sassBackend + '/**/*.{sass,scss}', ['sassBackend']);
});

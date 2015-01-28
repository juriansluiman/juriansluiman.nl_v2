var gulp   = require('gulp'),
    concat = require('gulp-concat');
    minify = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    chmod  = require('gulp-chmod'),
    watch  = require('gulp-watch');

gulp.task('styles', function () {
    gulp.src(['public/bower_components/normalize.css/normalize.css', 'public/bower_components/humane-js/themes/libnotify.css', 'public/bower_components/skeleton-css/css/skeleton.css', 'public/styles/fonts/fonts.css', 'public/styles/src/juriansluiman.nl.css'])
        .pipe(concat('styles.css'))
        .pipe(minify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/styles/dist/'));
});

gulp.task('scripts', function () {
    gulp.src(['public/scripts/src/*.js', 'public/bower_components/lodash/lodash.js', 'public/bower_components/humane-js/humane.js'])
        .pipe(concat('main.js'))
        .pipe(uglify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/scripts/dist/'));
})

gulp.task('default', ['styles', 'scripts']);

gulp.task('watch', ['default'], function () {
    gulp.watch('public/styles/src/*.css', ['styles']);
    gulp.watch(['public/scripts/src/*.js', 'public/scripts/src/*.tag'], ['scripts']);
});

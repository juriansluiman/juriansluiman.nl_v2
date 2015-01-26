var gulp   = require('gulp'),
    concat = require('gulp-concat');
    minify = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    chmod  = require('gulp-chmod');

gulp.task('default', function() {
    gulp.src(['public/bower_components/normalize.css/normalize.css', 'public/bower_components/humane-js/themes/libnotify.css', 'public/bower_components/skeleton-css/css/skeleton.css', 'public/styles/fonts/fonts.css', 'public/styles/src/juriansluiman.nl.css'])
        .pipe(concat('styles.css'))
        .pipe(minify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/styles/dist/'));

    gulp.src('public/styles/src/prism.css')
        .pipe(minify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/styles/dist/'));

    gulp.src('public/styles/src/admin.css')
        .pipe(minify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/styles/dist/'));

    gulp.src(['public/scripts/src/prism.js', 'public/bower_components/humane-js/humane.js'])
        .pipe(concat('main.js'))
        .pipe(uglify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/scripts/dist/'));

    return;
});

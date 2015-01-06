var gulp   = require('gulp'),
    concat = require('gulp-concat');
    minify = require('gulp-minify-css')
    uglify = require('gulp-uglify');

gulp.task('default', function() {
    gulp.src(['public/bower_components/normalize.css/normalize.css', 'public/bower_components/skeleton-css/css/skeleton.css', 'public/styles/fonts/fonts.css', 'public/styles/src/juriansluiman.nl.css'])
        .pipe(concat('styles.css'))
        .pipe(minify())
        .pipe(gulp.dest('public/styles/dist/'));

    gulp.src('public/styles/src/admin.css')
        .pipe(minify())
        .pipe(gulp.dest('public/styles/dist/'));

    gulp.src('public/scripts/src/prism.js')
        .pipe(uglify())
        .pipe(gulp.dest('public/scripts/dist/'));

    return;
});

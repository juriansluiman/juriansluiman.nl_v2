var gulp   = require('gulp'),
    concat = require('gulp-concat');
    minify = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    riot   = require('gulp-riot'),
    chmod  = require('gulp-chmod'),
    watch  = require('gulp-watch')
    gulpif = require('gulp-if');

gulp.task('styles', function () {
    var styles = [
        'public/bower_components/normalize.css/normalize.css',
        'public/bower_components/humane-js/themes/libnotify.css',
        'public/bower_components/skeleton-css/css/skeleton.css',
        'public/styles/fonts/fonts.css',
        'public/styles/src/font-awesome.css',
        'public/styles/src/juriansluiman.nl.css'
    ];
    gulp.src(styles)
        .pipe(concat('styles.css'))
        .pipe(minify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/styles/dist/'));
});

gulp.task('scripts', function () {
    var scripts = [
        'public/scripts/src/*.js',
        'public/bower_components/lodash/lodash.js',
        'public/bower_components/humane-js/humane.js'
    ];
    gulp.src(scripts)
        .pipe(concat('main.js'))
        .pipe(uglify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/scripts/dist/'));

    var scripts = [
        'public/scripts/src/fonts.js',
        'node_modules/medium-editor/src/js/medium-editor.js'
    ];
    gulp.src(scripts)
        .pipe(concat('admin.js'))
        //.pipe(uglify())
        .pipe(chmod(644))
        .pipe(gulp.dest('public/scripts/dist'));

    gulp.src(['public/bower_components/riot/riot.js', 'public/scripts/src/*.tag'])
        .pipe(gulpif('*.tag', riot()))
        //.pipe(uglify())
        .pipe(concat('riot.js'))
        .pipe(chmod(644))
        .pipe(gulp.dest('public/scripts/dist'));
})

gulp.task('default', ['styles', 'scripts']);

gulp.task('watch', ['default'], function () {
    gulp.watch('public/styles/src/*.css', ['styles']);
    gulp.watch(['public/scripts/src/*.js', 'public/scripts/src/*.tag'], ['scripts']);
});

'use strict';

var gulp        = require('gulp'),
    concat      = require('gulp-concat'),
    jslint      = require('gulp-jslint'),
    livereload  = require('gulp-livereload'),
    sass        = require('gulp-sass'),
    sourcemaps  = require('gulp-sourcemaps'),
    pump        = require('pump'),
    uglify      = require('gulp-uglify');

//Run sass compressed without maps
gulp.task('sass:pro', function (){
    return gulp.src('./source/sass/styles.scss')
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(gulp.dest('./assets/css/'))
        .pipe(livereload());
});

//Run sass expanded with maps
gulp.task('sass:dev', function (){
    return gulp.src('./source/sass/styles.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
        .pipe(sourcemaps.write('./map'))
        .pipe(gulp.dest('./assets/css/'))
        .pipe(livereload());
});

//Concatenate scripts together
gulp.task('scripts:dev', function(cb) {
    pump([
            gulp.src('./source/javascript/*.js'),
            concat('scripts.js'),
            gulp.dest('./assets/javascript')
        ],
        cb
    );
});

//Concatenate scripts together and minify
gulp.task('scripts:pro', function(cb) {
    pump([
            gulp.src('./source/javascript/*.js'),
            concat('scripts.js'),
            uglify(),
            gulp.dest('./assets/javascript')
        ],
        cb
    );
});

//Check scripts for errors
gulp.task('scripts:lint', function () {
    return gulp.src('assets/javascript/scripts.js')
        .pipe(jslint({ /* this object represents the JSLint directives being passed down */ }))
        .pipe(jslint.reporter( 'default'));
});

//Watch for changes in sass or js files
gulp.task('watch:pro', ['sass:pro','scripts:pro','watch:pro:listen']);

//Watch for sass changes and run the sass task
gulp.task('watch:pro:listen', function () {
    livereload.listen();

    gulp.watch('./source/sass/**/*.scss', ['sass:pro']);
    gulp.watch('./source/javascript/**/*.js', ['scripts:pro']);
});

//Watch for changes in sass or js files
gulp.task('watch:dev', ['sass:dev','scripts:dev','watch:dev:listen']);

//Watch for sass changes and run the sass task
gulp.task('watch:dev:listen', function () {
    livereload.listen();
    gulp.watch('./source/sass/**/*.scss', ['sass:dev']);
    gulp.watch('./source/javascript/**/*.js', ['scripts:dev']);
});

//Display List of commands
gulp.task('help', function(){
    console.log('');
    console.log('  Commands for gulp');
    console.log('');
    console.log('  Command              Description');
    console.log('  --------------------------------');
    console.log('  gulp watch:pro            Run sass and js for pro');
    console.log('  gulp watch:dev            Run sass and js for dev');
    console.log('  gulp sass:pro             Run sass for production environment');
    console.log('  gulp sass:dev             Run sass for development environment');
    console.log('  gulp scripts:pro          Run scripts for production environment');
    console.log('  gulp scripts:dev          Run scripts for development environment');
    console.log('  gulp scripts:lint         Run lint over the output scripts');
    console.log('');
});

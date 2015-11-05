var gulp = require('gulp'),
    less = require('gulp-less'),
    sass = require('gulp-sass'),
    minify = require('gulp-minify-css'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    notify = require('gulp-notify'),
    growl = require('gulp-notify-growl'),
    sass = require('gulp-sass');

var paths = {
 'dev': {
  'less': './resources/assets/less/',
  'sass': './resources/assets/sass/',
  'js': './resources/assets/js/',
  'bower': './bower_components/'
 },
 'production': {
  'css': './public/assets/css/',
  'js': './public/assets/js/'
 }
};

gulp.task('css', function() {
 return gulp.src(paths.dev.less+'app.less')
     .pipe(less())
     .pipe(minify({keepSpecialComments:0}))
     .pipe(rename({suffix: '.min'}))
     .pipe(gulp.dest(paths.production.css));
});

gulp.task('js', function(){
 return gulp.src([
  paths.dev.bower+'jquery/dist/jquery.js',
  paths.dev.bower+'bootstrap/dist/js/bootstrap.js',
  paths.dev.bower+'Chart.js/Chart.js',
  paths.dev.bower+'isotope/dist/isotope.pkgd.js',
  paths.dev.js+'general.js',
  paths.dev.js+'collection.js',
  paths.dev.js+'chart.js'
 ])
     .pipe(concat('app.min.js'))
     .pipe(gulp.dest(paths.production.js));
});

gulp.task('watch', function() {
 gulp.watch(paths.dev.less + '/*.less', ['css']);
 gulp.watch(paths.dev.js + '/*.js', ['js']);
});

gulp.task('default', ['css', 'js', 'watch']);
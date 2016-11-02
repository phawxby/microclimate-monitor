var gulp = require('gulp');
var sass = require('gulp-sass');
var gutil = require('gulp-util');
var ts = require('gulp-typescript');
var rename = require("gulp-rename");

/* ------------------------------------------------- */
 
gulp.task('ts', function () {
    return gulp.src('./public/ts/**/*.ts')
        .pipe(ts({
            noImplicitAny: true,
            target : "ES6"
        }).on('error', gutil.log))
        .pipe(gulp.dest('./public/js'));
});

gulp.task('watch:ts', function () {
  gulp.watch('./public/ts/**/*.ts', ['ts']);
});

/* ------------------------------------------------- */

gulp.task('js:compress', ['ts'],function() {
  return gulp.src(['./public/js/**/*.js', '!./public/js/**/*.min.js'])
    .pipe(rename({
      suffix: ".min"
    }))
    .pipe(gulp.dest('./public/js'));
});

gulp.task('watch:js', function () {
  gulp.watch(['./public/js/**/*.js', '!./public/js/**/*.min.js'], ['js:compress']);
});

/* ------------------------------------------------- */
 
gulp.task('sass', function () {
  return gulp.src('./public/sass/**/*.scss')
    .pipe(sass().on('error', gutil.log))
    .pipe(gulp.dest('./public/css'));
});
 
gulp.task('watch:sass', function () {
  gulp.watch('./public/**/*.scss', ['sass']);
});

/* ------------------------------------------------- */

gulp.task('build', ['js:compress', 'ts', 'sass']);

gulp.task('watch', ['build', 'watch:sass', 'watch:ts', 'watch:js']);
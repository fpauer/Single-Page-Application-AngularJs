
var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    ngAnnotate = require('gulp-ng-annotate'),
    connect = require('gulp-connect');

// compact js
gulp.task('js', function () {
    gulp.src([
              'public/js/modules/showErrors.js'
            , 'public/js/modules/logger.js'
            , 'public/js/app.js'
            , 'public/js/services/modalService.js'
            , 'public/js/directives/validNumberDirective.js'
            , 'public/js/authController.js'
            , 'public/js/mealsController.js'
            , 'public/js/usersController.js'
            , 'public/js/settingsController.js'])
        .pipe(concat('all.min.js'))
        .pipe(ngAnnotate())
        .pipe(uglify({mangle: false}))
        .pipe(gulp.dest('public/js'))
});
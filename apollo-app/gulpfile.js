/**
 * gulpfile for development purposes
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
var gulp = require('gulp');
function swallowError (error) {
    console.log(error.toString());
    this.emit('end');
}

// var sass = require('gulp-sass');
// var autoprefixer = require('gulp-autoprefixer');
// gulp.task('sass', function () {
//     return gulp.src('src/sass/**/*.sass')
//         .pipe(sass())
//         .on('error', swallowError)
//         .pipe(autoprefixer())
//         .pipe(gulp.dest('assets/css'))
//         .pipe(browserSync.reload({
//             stream: true
//         }))
// });

// var ts = require('gulp-typescript');
// var tsProject = ts.createProject('tsconfig.json');
// gulp.task('ts', function () {
//     var tsResult = tsProject.src()
//         .pipe(ts(tsProject));
//     return tsResult.js
//         .pipe(gulp.dest('assets/js'))
//         .pipe(browserSync.reload({
//             stream: true
//         }));
// });

var browserSync = require('browser-sync').create();
gulp.task('browserSync', function () {
    browserSync.init({
        files: 'apollo/assets/css/**/*.css',
        open: 'external',
        host: 'localhost',
        proxy: 'apollo.dev:8888',
        port: 3000
    })
});

gulp.task('default', ['browserSync'/*, 'sass', 'ts'*/], function () {
    //gulp.watch('src/sass/**/*.sass', ['sass']);
    //gulp.watch('src/ts/**/*.ts', ['ts']);
    gulp.watch("apollo/assets/css/**/*.css").on('change', browserSync.reload({
        stream: true
    }));
    gulp.watch("apollo/assets/js/**/*.js").on('change', browserSync.reload({
        stream: true
    }));
});
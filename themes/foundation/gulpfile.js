var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('sass', function() {
    gulp.src('./assets/scss/foundation.scss')
        .pipe(sass())
        .pipe(gulp.dest('./assets/css')); 
});

gulp.task('compile', function() {
	gulp.src( './assets/scss/*.scss' )
		.pipe(sass())
		.pipe(gulp.dest('./assets/css/'));
});

gulp.task('watch', function() {
    gulp.watch('./assets/scss/**', ['sass']);
});
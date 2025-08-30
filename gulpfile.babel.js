import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as sass from 'sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import browserSync from 'browser-sync';
// import esbuild from 'gulp-esbuild';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import zip from 'gulp-zip';

const { src, dest, watch, series } = gulp;
const sassCompiler = gulpSass(sass);
const bs = browserSync.create();

// === Paths ===  
const paths = {
  scss: {
    src: 'src/scss/**/*.scss',
    dest: 'dist/css',
  },
  js: {
    entry: 'src/js/main.js',
    watch: 'src/js/**/*.js', 
    dest: 'dist/js',
  },
  php: '**/*.php',
  package: {
    src: ['**/*', '!src{,/**}', '!node_modules{,/**}', '!.gitattributes', '!.gitignore', '!.prettierrc', '!.babelrc', '!gulpfile.babel.js', '!package.json', '!package-lock.json'],
    dest: 'production'
  }
};

// === SCSS Task ===
export const compileSCSS = () => {
  return src(paths.scss.src)
    .pipe(sassCompiler({ outputStyle: 'expanded' }).on('error', sassCompiler.logError))
    .pipe(postcss([autoprefixer()]))
    .pipe(dest(paths.scss.dest))
    .pipe(bs.stream());
}

// === JS Task ESMODULE WAS FAILING ===
// function bundleJS(cb) {
//   src(paths.js.entry)
//     .pipe(esbuild({
//       bundle: true,
//       minify: true,
//       sourcemap: true,
//       outfile: 'bundle.js',
//       target: ['es2015'],
//       platform: 'browser',
//     }))
//     .pipe(dest(paths.js.dest))
//     .on('end', cb)
//     .on('error', cb);
// }

export const bundleJS = () => {
  return src('src/js/**/*.js')
    .pipe(concat('bundle.js'))
    .pipe(uglify())
    .pipe(dest(paths.js.dest));
}


// === Browser Reload Helper ===
export const reload = (done) => {
  bs.reload();
  done();
}

// === BrowserSync + Watch ===
export const serve = (done) => {
  bs.init({
    proxy: 'http://localhost/web',
    open: true,
    notify: true,
    injectChanges: true,
  });
  watch(paths.scss.src, compileSCSS);
  watch(paths.js.watch, series(bundleJS, reload));
  watch(paths.php).on('change', bs.reload);
  done();
}

// === bundle/compress file for production
export const compress = () => {
   return gulp.src(paths.package.src)
  .pipe(zip('sms-app.zip'))
  .pipe(gulp.dest(paths.package.dest));
}

export default series(compileSCSS, bundleJS, serve);
export const bundle = gulp.series(compileSCSS, bundleJS, compress);
import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as sass from 'sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import browserSync from 'browser-sync';
// import esbuild from 'gulp-esbuild';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';

const { src, dest, watch, series } = gulp;
const sassCompiler = gulpSass(sass);
const bs = browserSync.create();

// === Paths ===  
const paths = {
  scss: {
    src: 'lib/scss/**/*.scss',
    dest: 'dist/css',
  },
  js: {
    entry: 'lib/js/main.js',
    watch: 'lib/js/**/*.js', 
    dest: 'dist/js',
  },
  php: '**/*.php',
};

// === SCSS Task ===
function compileSCSS() {
  return src(paths.scss.src)
    .pipe(sassCompiler({ outputStyle: 'expanded' }).on('error', sassCompiler.logError))
    .pipe(postcss([autoprefixer()]))
    .pipe(dest(paths.scss.dest))
    .pipe(bs.stream());
}

// === JS Task ===
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
function bundleJS() {
  return src('lib/js/**/*.js')
    .pipe(concat('bundle.js'))
    .pipe(uglify())
    .pipe(dest(paths.js.dest));
}


// === Browser Reload Helper ===
function reload(done) {
  bs.reload();
  done();
}

// === BrowserSync + Watch ===
function serve(done) {
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

// === Public Tasks ===
export { compileSCSS as scss };
export { bundleJS as js };
export default series(compileSCSS, bundleJS, serve);
'use strict';

const gulp = require('gulp'),
  webpackStream = require('webpack-stream'),
  webpackMerge = require('webpack-merge'),
  named = require('vinyl-named'),
  csscompile = require('gulp-sass'),
  postcss = require('gulp-postcss'),
  autoprefixer = require('autoprefixer'),
  cssnano = require('cssnano'),
  svgsprite = require('gulp-svg-sprite'),
  header = require('gulp-header'),
  rename = require('gulp-rename'),
  del = require('del'),
  browsersync = require('browser-sync').create(),
  zip = require('gulp-zip'),
  gulputil = require('gulp-util'),
  os = require('os'),

  webpackBaseConf = require('./.build/webpack.base.conf'),
  webpackDevConf = require('./.build/webpack.dev.conf'),
  webpackProdConf = require('./.build/webpack.prod.conf'),
  packageJson = require('./package.json');

var isVM = (os.userInfo().username == "vagrant") ? true : false,
  host = (isVM) ? 'localhost' : 'localhost/' + packageJson.name,
  src = 'src',
  srcAssets = src + '/assets',
  dist = 'dist',
  distAssets = dist + '/assets';

var js = [
  // requirejs
  'node_modules/requirejs/require.js',
  // jquery
  'node_modules/jquery/dist/jquery.js',
  // bootstrap dependency
  'node_modules/popper.js/dist/umd/popper.js',
  // bootstrap
  'node_modules/bootstrap/js/dist/util.js',
  'node_modules/bootstrap/js/dist/alert.js',
  'node_modules/bootstrap/js/dist/collapse.js',
  'node_modules/bootstrap/js/dist/dropdown.js',
  // photoSwipe
  'node_modules/photoswipe/dist/photoswipe.js',
  'node_modules/photoswipe/dist/photoswipe-ui-default.js',
  // js-cookie
  'node_modules/js-cookie/src/js.cookie.js',
  // app
  // srcAssets + '/js/app.js'
];

var author = packageJson.author,
  authorName = author.substring( 0, (author.lastIndexOf("(")-1) ),
  authorUrl = author.substring( (author.lastIndexOf("(")+1), author.lastIndexOf(")") ),
  banner = ["/**",
    " * Theme Name: " + packageJson.name,
    " * Author: " + authorName,
    " * Author URI: " + authorUrl,
    " * Version: " + packageJson.version,
  " */\n"].join("\n");

var copyChanged = (obj) => {
  if ( obj.type === 'changed') {
    return gulp.src(obj.path, {base: src})
      .pipe(gulp.dest(dist))
      .on('end', () => gulputil.log('Finished', "'" + gulputil.colors.cyan('copyChanged') + "'", obj.path) );
  }
};



gulp.task('clean', () => {
  return del(
      [
        dist + '/**/*',
        '!' + dist + '/.git/'
      ],
      {force: true}
    );
});

gulp.task('php', () => {
  return gulp.src(src + '/**/*.php')
    .pipe(gulp.dest(dist));
});

gulp.task('js-compile', () => {
  return gulp.src(srcAssets + '/js/*.js')
    .pipe(named())
    .pipe(webpackStream(
      webpackMerge(webpackBaseConf, webpackDevConf)
    ))
    .pipe(gulp.dest(distAssets + '/js/'));
});

gulp.task('js', () => {
  return gulp.src(srcAssets + '/js/*.js')
    .pipe(named())
    .pipe(webpackStream(
      webpackMerge(webpackBaseConf, webpackProdConf)
    ))
    .pipe(gulp.dest(distAssets + '/js/'));
});

gulp.task('css-compile', () => {
  return gulp.src(srcAssets + '/scss/compile.scss')
    .pipe(csscompile())
    .pipe(rename('style.css'))
    .pipe(header(banner))
    .pipe(gulp.dest(dist));
});

gulp.task('css', ['css-compile'], () => {
  return gulp.src(dist + '/style.css')
    .pipe(postcss([
      autoprefixer({browsers: ['last 1 version']}),
      cssnano({zindex: false})
    ]))
    .pipe(header(banner))
    .pipe(gulp.dest(dist));
});

gulp.task('svg-icons', () => {
  return gulp.src('*', {cwd: srcAssets + '/svg/icons/'})
    .pipe(svgsprite({
      shape: {
        id: { separator: '-', generator: 'icon-%s' },
        dimension: { maxWidth: 32, maxHeight: 32 }
      },
      mode: {
        symbol: { dest: '', sprite: 'icons.svg' }
      }
    }))
    .pipe(gulp.dest(distAssets + '/svg/'));
});

gulp.task('assets', ['svg-icons'], () => {
  return gulp.src(
      [
        srcAssets + '/{fonts,imgs,css}/**/*',
        srcAssets + '/svg/*.svg',
        src + '/screenshot.png',
        '!' + srcAssets + '/{fonts,imgs}/*.{json,psd}'
      ],
      {base: srcAssets}
    )
    .pipe(gulp.dest(distAssets));
});

gulp.task('serve', () => {
  return browsersync.init({
    proxy: host,
    open: (isVM) ? false : true,
    files: dist + '/**/*',
    notify: false,
    reloadDelay: 500,
    ghostMode: false
  });
});

gulp.task('watch', () => {
  gulp.watch(src + '/**/*.php', copyChanged);
  gulp.watch(srcAssets + '/js/**', ['js-compile']);
  gulp.watch(srcAssets + '/scss/**/*', ['css-compile']);
  gulp.watch(srcAssets + '/svg/icons/**/*', ['svg-icons']);
  gulp.watch(
    [srcAssets + '/{fonts,imgs,css}/**/*', srcAssets + '/svg/*.svg', src + '/screenshot.png'],
    ['assets']
  );
});



gulp.task('dev', ['php', 'js-compile', 'css-compile', 'assets']);
gulp.task('dist', ['clean'], () => {
  gulp.start(['php', 'js', 'css', 'assets']);
});
gulp.task('zip', () => {
  return gulp.src(dist + '/**')
    .pipe(zip('dist.zip'))
    .pipe(gulp.dest('.data'));
});

gulp.task('default', ['dev', 'serve'], () => {
  gulp.start(['watch']);
});

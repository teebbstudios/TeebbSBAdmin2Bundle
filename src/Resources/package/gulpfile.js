"use strict";

// Load plugins
const autoprefixer = require("gulp-autoprefixer");
const cleanCSS = require("gulp-clean-css");
const del = require("del");
const gulp = require("gulp");
const header = require("gulp-header");
const merge = require("merge-stream");
const plumber = require("gulp-plumber");
const rename = require("gulp-rename");
const sass = require("gulp-sass");
const uglify = require("gulp-uglify");

// Load package.json for banner
const pkg = require('./package.json');

// Set the banner content
const banner = ['/*!\n',
    ' * Start Bootstrap - <%= pkg.title %> v<%= pkg.version %> (<%= pkg.homepage %>)\n',
    ' * Copyright 2013-' + (new Date()).getFullYear(), ' <%= pkg.author %>\n',
    ' * Licensed under <%= pkg.license %> (https://github.com/BlackrockDigital/<%= pkg.name %>/blob/master/LICENSE)\n',
    ' */\n',
    '\n'
].join('');

// Clean vendor
function clean() {
    return del(["./public/bundles/teebbsbadmin2/vendor/"]);
}

// Bring third party dependencies from node_modules into vendor directory
function modules() {
    // Bootstrap JS
    var bootstrapJS = gulp.src('./node_modules/bootstrap/dist/js/*')
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/bootstrap/js'));
    // Bootstrap SCSS
    var bootstrapSCSS = gulp.src('./node_modules/bootstrap/scss/**/*')
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/bootstrap/scss'));
    // ChartJS
    var chartJS = gulp.src('./node_modules/chart.js/dist/*.js')
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/chart.js'));
    // dataTables
    var dataTables = gulp.src([
        './node_modules/datatables.net/js/*.js',
        './node_modules/datatables.net-bs4/js/*.js',
        './node_modules/datatables.net-bs4/css/*.css'
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/datatables'));
    // Font Awesome
    var fontAwesome = gulp.src('./node_modules/@fortawesome/**/*')
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor'));
    // jQuery Easing
    var jqueryEasing = gulp.src('./node_modules/jquery.easing/*.js')
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/jquery-easing'));
    // jQuery
    var jquery = gulp.src([
        './node_modules/jquery/dist/*',
        '!./node_modules/jquery/dist/core.js'
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/jquery'));
    var sweetalert2 = gulp.src([
        './node_modules/sweetalert2/dist/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/sweetalert2'));

    var sortableJs = gulp.src([
        './node_modules/sortablejs/*.js',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/sortablejs'));

    var bootstrapSelect = gulp.src([
        './node_modules/bootstrap-select/dist/**/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/bootstrap-select'));

    var transliteration = gulp.src([
        './node_modules/transliteration/dist/browser/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/transliteration'));

    var dropzone = gulp.src([
        './node_modules/dropzone/dist/**/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/dropzone'));

    var fabric = gulp.src([
        './node_modules/fabric/dist/**/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/fabric'));

    var tuiCodeSnippet = gulp.src([
        './node_modules/tui-code-snippet/dist/**/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/tui-code-snippet'));

    var tuiColorPicker = gulp.src([
        './node_modules/tui-color-picker/dist/**/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/tui-color-picker'));

    var tuiImageEditor = gulp.src([
        './node_modules/tui-image-editor/dist/**/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/tui-image-editor/dist'));

    var tuiImageEditorExamples = gulp.src([
        './node_modules/tui-image-editor/examples/**',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/tui-image-editor/examples'));

    var fileSaver = gulp.src([
        './node_modules/file-saver/dist/**/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/file-saver'));

    var videoJs = gulp.src([
        './node_modules/video.js/dist/**/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/video.js'));

    var nestable = gulp.src([
        './node_modules/nestable/*',
    ])
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/vendor/nestable'));


    return merge(bootstrapJS, bootstrapSCSS, chartJS, dataTables, fontAwesome, jquery, jqueryEasing,
        sweetalert2, sortableJs, bootstrapSelect, transliteration, dropzone, fabric, tuiCodeSnippet, tuiColorPicker,
        fileSaver, tuiImageEditor, tuiImageEditorExamples, videoJs, nestable,
        );
}

// CSS task
function css() {
    return gulp
        .src("./public/bundles/teebbsbadmin2/scss/**/*.scss")
        .pipe(plumber())
        .pipe(sass({
            outputStyle: "expanded",
            includePaths: "./node_modules",
        }))
        .on("error", sass.logError)
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(header(banner, {
            pkg: pkg
        }))
        .pipe(gulp.dest("./public/bundles/teebbsbadmin2/css"))
        .pipe(rename({
            suffix: ".min"
        }))
        .pipe(cleanCSS())
        .pipe(gulp.dest("./public/bundles/teebbsbadmin2/css"));

}

// JS task
function js() {
    return gulp
        .src([
            './public/bundles/teebbsbadmin2/js/*.js',
            './public/bundles/teebbsbadmin2/js/**/*.js',
            '!./public/bundles/teebbsbadmin2/js/*.min.js',
            '!./public/bundles/teebbsbadmin2/js/**/*.min.js',
        ])
        .pipe(uglify())
        .pipe(header(banner, {
            pkg: pkg
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('./public/bundles/teebbsbadmin2/js'));

}

// Watch files
function watchFiles() {
    gulp.watch("./public/bundles/teebbsbadmin2/scss/**/*", css);
    gulp.watch("./public/bundles/teebbsbadmin2/js/**/*", js);
}

// Define complex tasks
const vendor = gulp.series(clean, modules);
const build = gulp.series(vendor, gulp.parallel(css, js));

// Export tasks
exports.css = css;
exports.js = js;
exports.clean = clean;
exports.vendor = vendor;
exports.build = build;
exports.default = build;

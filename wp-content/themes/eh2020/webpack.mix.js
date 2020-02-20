let mix = require("laravel-mix");
const glob = require("glob-all");

/* ==========================================================================
  Config
  ========================================================================== */
const config = {
  siteUrl: "eh2020.test",
  proxyUrl: "https://eh2020.test",
  port: 3000,
  openOnStart: false,
  pathToLocalSSLCert: "",
  pathToLocalSSLKey: "",
  filesToWatch: [
    "./**/*.php",
    "./**/*.html",
    "src/scss/**/*.scss",
    "src/js/**/*.js",
    "src/img/*",
    "src/js/components/**/*.vue",
    "src/tailwind-config.js"
  ]
};

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

if (mix.inProduction()) {
  mix
    .minify("dist/app.css")
    .minify("dist/app.js")
    .minify("dist/vendor.js")
    .minify("dist/manifest.js")
    .sourceMaps();
} else {
  mix
    .options({
      processCssUrls: false
    })
    .js("src/app.js", "dist/")
    .extract(["bootstrap", "vue", "v8n"])
    .sass("src/app.scss", "dist/")
    .sass("src/custom-editor-style.scss", ".")
    // .version()
    .sourceMaps()
    .browserSync({
      proxy: config.proxyUrl,
      host: config.siteUrl,
      open: config.openOnStart,
      port: config.port,
      https: {
        key: config.pathToLocalSSLKey,
        cert: config.pathToLocalSSLCert
      },
      files: config.filesToWatch
    });
}

const Encore = require('@symfony/webpack-encore');
const path = require('path');
// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    .copyFiles({
        from: './assets/images',
        pattern: /\.(png|jpg|jpeg|svg|webp)$/,
        // to path is relative to the build directory
        to: 'images/[path][name].[ext]'
    })

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/homepage', './assets/js/homepage.js')
    .addEntry('js/share', './assets/js/share.js')
    .addEntry('js/contact', './assets/js/contact.js')
    .addEntry('js/profile', './assets/js/profile.js')
    .addEntry('js/admin', './assets/js/admin/admin.js')
    .addEntry('js/edit', './assets/js/admin/edit.js')
    .addEntry('js/new', './assets/js/admin/new.js')
    .addStyleEntry('css/app', './assets/scss/app.scss')
    .addStyleEntry('css/admin/edit', './assets/scss/admin/edit.scss')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // Enable PostCSS Support
    .enablePostCssLoader()

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    .enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    .configureDevServerOptions((options) => {
        options.liveReload = true;
        options.hot = true;
        options.watchFiles = [
            './templates/**/*',
            './src/**/*'
        ]
        // options.server = {
        //     type: 'https',
        //     options: {
        //         pfx: path.join(process.env.HOME, '.symfony/certs/default.p12')
        //     }
        // }
        options.allowedHosts = 'all';

    });

module.exports = Encore.getWebpackConfig();
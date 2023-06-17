const mix = require('laravel-mix');


mix.webpackConfig({
    output: {
        path:__dirname+'/../../dist/admin',
    }
});

//mix.setPublicPath('../dist/admin');

mix
    .sass('scss/app.scss', 'css')
    .sass('../../module/page/admin/scss/builder.scss', 'module/page/css');
mix.js('js/app.js','js').extract(['vue']).vue({ version: 2 });

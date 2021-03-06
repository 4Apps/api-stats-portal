const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
// const dotenv = require('dotenv');

const config = {
    target: 'browserslist',
    mode: 'production',
    context: path.resolve(__dirname, 'src/Application/Public'),
    entry: {
        base: './assets/src/base/js/_module.js',
        stats: './assets/src/stats/_module.js',
    },
    output: {
        devtoolModuleFilenameTemplate: '[resource-path]?[loaders]',
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'src/Application/Public/assets/dist/js/'),
        libraryTarget: 'var',
        library: '[name]Module',
    },
    resolve: {
        alias: {
            oneui: path.resolve(
                __dirname,
                'src/Application/Public/assets/src/one-ui/_js/main'
            ),
            utils: path.resolve(
                __dirname,
                'src/Application/Public/assets/src/base/js/utils.js'
            ),
            customPolyfill: path.resolve(
                __dirname,
                'src/Application/Public/assets/src/base/js/customPolyfill.js'
            ),
            datePicker: path.resolve(
                __dirname,
                'src/Application/Public/assets/src/base/js/datePicker.js'
            ),
        },
    },
    module: {
        rules: [
            {
                test: /\.js*$/,
                use: {
                    loader: 'strip-trailing-space-loader',
                    options: {
                        line_endings: 'unix',
                    },
                },
            },
        ],
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendors: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    chunks: 'all',
                },
                commons: {
                    test: /src\/Application\/Public\/assets\/src\/base\/js/,
                    name: 'commons',
                    chunks: 'all',
                },
            },
        },
        usedExports: true,
        sideEffects: true,
        minimize: false,
        minimizer: [
            new TerserPlugin({
                terserOptions: {
                    output: {
                        ecma: 6,
                        comments: false,
                    },
                },
            }),
        ],
    },
    stats: {
        colors: true,
    },
    plugins: [
        new webpack.IgnorePlugin({
            resourceRegExp: /^\.\/locale$/,
            contextRegExp: /moment$/,
        }),
        new ESLintPlugin(),
    ],
    devtool: 'source-map',
    watchOptions: {
        ignored: /node_modules/,
    },
};

module.exports = (env, argv) => {
    // // Load .env file
    // if (argv.mode === 'production') {
    //     dotenv.config({ path: './src/Application/.env.prod' });
    // } else {
    //     dotenv.config({ path: './src/Application/.env' });
    // }
    config.plugins.push(
        new webpack.DefinePlugin({
            APP_ENV: JSON.stringify(argv.mode),
        })
    );

    if (argv.mode === 'production') {
        config.output.filename = '[name].min.js';
        config.optimization.minimize = true;
    }

    return config;
};

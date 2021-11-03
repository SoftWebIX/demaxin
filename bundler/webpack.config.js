const path                    = require( 'path' );
const UglifyJSPlugin          = require( 'uglifyjs-webpack-plugin' );
const OptimizeCssAssetsPlugin = require( 'optimize-css-assets-webpack-plugin' );
const MiniCssExtractPlugin    = require( 'mini-css-extract-plugin' );

const env = process.env.NODE_ENV;

const entryPoint = {
	'customizer': './assets/js/modules/customizer.js',
	'navigation': './assets/js/modules/navigation.js',
	'app': './assets/js/app.js',
	'editor-blocks' : './assets/js/gutenberg/blocks.js',
	'frontend-blocks' : './assets/js/gutenberg/frontend.js',
	'events' : './assets/js/modules/events.js'
}

const defaultConfig = {
	mode: env || 'development',
	entry: entryPoint,
	output: {
		path: path.resolve( __dirname, '../dist' ),
		filename: 'js/[name].min.js'
	},
	watch  : 'production' !== env,
	stats: { children: false },
	module: {
		rules: [
			{
				test: /\.js(x)?$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader'
				}
			},
			{
				test: /\.scss$/,
				exclude: /(node_modules|bower_components)/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'sass-loader'
				]
			}
		]
	},
	resolve: {
		extensions: [ '.js', '.jsx', '.scss' ]
	},
	externals: {
		'react': 'React',
		'react-dom': 'ReactDOM',
		'wp.i18n': {
			window: [ 'wp', 'i18n' ]
		},
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: 'css/[name].min.css'
		} ),
	]
};

if ( env === 'production' ) {
	defaultConfig.plugins.push(
		new UglifyJSPlugin(),
		new OptimizeCssAssetsPlugin()
	);
}

module.exports = defaultConfig;
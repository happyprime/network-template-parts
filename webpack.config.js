const path = require('path');
const glob = require('glob');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

/**
 * Retrieve all entries from subdirectories of the src directory.
 *
 * Valid entry points are index.js and view.js.
 *
 * @param {string} sourceDir The source directory.
 * @param {string} type The type of build in progress.
 * @returns {object} An object of entry points.
 */
const getEntries = (sourceDir, type) => {
	const entries = {};

	const filescan = 'blocks' === type ? '**/*(index|view).js' : '*.js';

	const files = glob.sync(sourceDir + '/' + filescan);

	files.forEach((file) => {
		const relativePath = path.relative(sourceDir, file);
		const entry = path.join(
			path.dirname(relativePath),
			path.basename(relativePath, '.js')
		);
		entries[entry] = './' + file;
	});

	return entries;
};

module.exports = (env) => {
	// Default to `blocks` if no type is provided. This adjusts the
	// entry point search pattern to look for blocks and accounts for
	// the block.json file during build.
	const type = env.type || 'blocks';

	// Default to `src` if no source directory is provided.
	const sourceDir = env.dir || 'src';

	// Default to `../build` if no build directory is provided.
	let buildDir = env.build || path.resolve(__dirname, sourceDir, '../build');

	return {
		entry: getEntries(sourceDir, type),
		output: {
			path: path.resolve(__dirname, buildDir),
			filename: '[name].js',
		},
		optimization: {
			minimize: true,
			minimizer: [
				new TerserPlugin({
					extractComments: false,
				}),
			],
		},
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /node_modules/,
					use: {
						loader: 'babel-loader',
						options: {
							presets: [
								'@babel/preset-env',
								'@babel/preset-react',
							],
							plugins: ['@babel/plugin-transform-runtime'],
						},
					},
				},
			],
		},
		plugins: [new DependencyExtractionWebpackPlugin()],

		// External dependencies that should not be bundled.
		externals: {
			react: 'React',
			'react-dom': 'ReactDOM',
		},
	};
};

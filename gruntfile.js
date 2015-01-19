module.exports = function(grunt) {


	// Set some vars
	var themeName  = '<%= pkg.name %>';
	var devDir  = 'src/';
	var distDir = 'public/';
	var themeDir = 'wp-content/themes/' + themeName + '/';
	var devThemeDir = devDir + themeDir;
	var distThemeDir = distDir + themeDir;

	// Set up the CSS files object
	var sassFilesObject = {};
	sassFilesObject[distThemeDir + 'styles/admin.css'] = devThemeDir + 'styles/admin.scss';
	sassFilesObject[distThemeDir + 'styles/main.css'] = devThemeDir + 'styles/main.scss';
	sassFilesObject[distThemeDir + 'styles/print.css'] = devThemeDir + 'styles/print.scss';


	// Define tasks
	grunt.initConfig({

		// Read in the grunt modules
		pkg: grunt.file.readJSON( 'package.json' ),

		// Process SASS
		sass: {
			options: {
				outputStyle: 'compressed',
				sourceMap: true,
			},
			default: {
				files: sassFilesObject
			}
		},

		// Copy to public
		copy: {
			php: {
				files: [{
					expand: true,
					cwd: devThemeDir,
					src: ['**/*.php'],
					dest: distThemeDir,
				}],
			},
			img: {
				files: [{
					expand: true,
					cwd: devThemeDir,
					src: ['**/*.gif', '**/*.jpg', '**/*.gif', '**/*.svg'],
					dest: distThemeDir,
				}],
			},
			js: {
				files: [{
					expand: true,
					cwd: devThemeDir,
					src: ['**/*.js'],
					dest: distThemeDir,
				}],
			},
			root: {
				files: [{
					expand: true,
					cwd: devDir,
					src: ['*'],
					dest: distDir,
				}],
			},
		},

		// Watch for changes
		watch: {
			styles: {
				files: [devThemeDir + 'styles/*.scss'],
				tasks: ['sass']
			},
			php: {
				files: [devThemeDir + '**/*.php'],
				tasks: ['php_changed']
			},
			img: {
				files: [devDir + '**/*.png', devDir + '**/*.jpg', devDir + '**/*.gif', devDir + '**/*.svg' ],
				tasks: ['img_changed']
			},
			js: {
				files: [devDir + '**/*.js'],
				tasks: ['js_changed']
			},
			root: {
				files:	[devDir + '*.php', devDir + '*.txt', devDir + '.ht*'],
				tasks:	['root_changed'],
			},
		}

	});


	// Load NPM tasks
	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

	// Register watch-related tasks
	grunt.registerTask( 'php_changed',			['copy:php'] );
	grunt.registerTask( 'img_changed',			['copy:img'] );
	grunt.registerTask( 'js_changed',			['copy:js'] );
	grunt.registerTask( 'root_changed',			['copy:root'] );

	// Other custom tasks
	grunt.registerTask( 'default', ['watch'] );


};

module.exports = function(grunt) {


	// Set some vars
	var themeName  = '<%= pkg.name %>';
	var devDir  = 'src';
	var distDir = 'public';
	var themeDir = 'wp-content/themes/' + themeName;
	var devThemeDir = devDir + '/' + themeDir;
	var distThemeDir = distDir + '/' + themeDir;
	var rootFiles = [ '.htaccess', '.htpasswd', '503.php', 'robots.txt', 'wp-config.php' ];

	// Set up the CSS files object
	var sassFilesObject = {};
	sassFilesObject[distThemeDir + '/styles/admin.css'] = devThemeDir + '/styles/admin.scss';
	sassFilesObject[distThemeDir + '/styles/main.css'] = devThemeDir + '/styles/main.scss';
	sassFilesObject[distThemeDir + '/styles/print.css'] = devThemeDir + '/styles/print.scss';


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
					cwd: devDir,
					src: ['**/*.php'],
					dest: distDir,
				}],
			},
			img: {
				files: [{
					expand: true,
					cwd: devDir,
					src: ['**/*.gif', '**/*.jpg', '**/*.gif', '**/*.svg'],
					dest: distDir,
				}],
			},
			js: {
				files: [{
					expand: true,
					cwd: devDir,
					src: ['**/*.js'],
					dest: distDir,
				}],
			},
			root: {
				files: [{
					cwd: devDir,
					src: rootFiles,
					dest: distDir,
				}],
			},
		},

		// Watch for changes
		watch: {
			styles: {
				files: [devThemeDir + '/styles/*.scss'],
				tasks: ['sass']
			},
			php: {
				files: [devThemeDir + '/**/*.php'],
				tasks: ['php_changed']
			},
			img: {
				files: [devDir + '/**/*.png', devDir + '/**/*.jpg', devDir + '/**/*.gif', devDir + '/**/*.svg' ],
				tasks: ['img_changed']
			},
			js: {
				files: [devDir + '/**/*.js'],
				tasks: ['js_changed']
			},
			root: {
				cwd:	devDir,
				files:	rootFiles,
				tasks:	['root_changed'],
			},
		}

	});


	// Load NPM tasks
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );

	// Register watch-related tasks
	grunt.registerTask( 'php_changed',			['copy:php'] );
	grunt.registerTask( 'img_changed',			['copy:img'] );
	grunt.registerTask( 'js_changed',			['copy:js'] );
	grunt.registerTask( 'root_changed',			['copy:root'] );

	grunt.registerTask( 'dev', ['sass'] );


};

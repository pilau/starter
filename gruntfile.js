module.exports = function(grunt) {


	// Set some vars
	var themeName  = '<%= pkg.name %>';
	var devDir  = 'src';
	var distDir = 'public';
	var themeDir = 'wp-content/themes/' + themeName;
	var devThemeDir = devDir + '/' + themeDir;
	var distThemeDir = distDir + '/' + themeDir;

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

		// Watch for changes
		watch: {
			styles: {
				files: devThemeDir + '/styles/*.scss',
				tasks: ['sass']
			}
		}

	});


	// Load tasks
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );

	// Register tasks
	grunt.registerTask( 'dev', ['sass'] );


};

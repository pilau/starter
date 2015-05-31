module.exports = function(grunt) {

	// Set some vars
	var themeName  = '<%= pkg.name %>';
	var srcDir  = 'src/';
	var publicDir = 'public/';
	var themeDir = 'wp-content/themes/' + themeName + '/';
	var srcThemeDir = srcDir + themeDir;
	var publicThemeDir = publicDir + themeDir;
	var breakpoints = require('./breakpoints.json');

	// Set up the CSS files object
	var sassFilesObject = {};
	sassFilesObject[publicThemeDir + 'styles/admin.css'] = publicThemeDir + 'styles/admin.scss';
	sassFilesObject[publicThemeDir + 'styles/main.css'] = publicThemeDir + 'styles/main.scss';
	sassFilesObject[publicThemeDir + 'styles/print.css'] = publicThemeDir + 'styles/print.scss';

	// Load NPM tasks
	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

	// Register watch-related tasks
	grunt.registerTask( 'styles_changed',		['copy:styles', 'sass', 'autoprefixer'] );
	grunt.registerTask( 'php_changed',			['copy:php'] );
	grunt.registerTask( 'img_changed',			['copy:img'] );
	grunt.registerTask( 'js_changed',			['copy:js'] );
	grunt.registerTask( 'fonts_changed',		['copy:fonts'] );
	grunt.registerTask( 'root_changed',			['copy:root'] );

	// Default tasks
	grunt.registerTask( 'default', ['watch'] );
	grunt.registerTask( 'init', ['copy:styles', 'sass', 'autoprefixer', 'copy:php', 'copy:img', 'copy:js', 'copy:fonts', 'copy:root'] );

	// Config tasks
	grunt.initConfig({

		// Read in the grunt modules
		pkg: grunt.file.readJSON( 'package.json' ),

		// Process SASS
		sass: {
			options: {
				outputStyle: 'compressed',
				sourceMap: true
			},
			default: {
				files: sassFilesObject
			}
		},

		// Autoprefixer
		autoprefixer: {
			options: {
				browsers: ['last 2 versions', 'ie >= 8'],
				cascade: false,
				map: true
			},
			default: {
				expand: true,
				flatten: true,
				src: publicThemeDir + '/styles/*.css',
				dest: publicThemeDir + '/styles/'
			}
		},

		// Copy to public
		copy: {
			styles: {
				files: [{
					expand: true,
					cwd: srcThemeDir,
					src: ['**/*.css', '**/*.scss'],
					dest: publicThemeDir
				}],
				options: {
					process: function ( content ) {
						content = content.replace( /pilauBreakpointLarge/g, breakpoints.large );
						content = content.replace( /pilauBreakpointMedium/g, breakpoints.medium );
						return content;
					}
				}
			},
			php: {
				files: [{
					expand: true,
					cwd: srcThemeDir,
					src: ['**/*.php'],
					dest: publicThemeDir
				}],
				options: {
					process: function ( content ) {
						content = content.replace( /pilauBreakpointLarge/g, breakpoints.large );
						content = content.replace( /pilauBreakpointMedium/g, breakpoints.medium );
						return content;
					}
				}
			},
			img: {
				files: [{
					expand: true,
					cwd: srcThemeDir,
					src: ['**/*.gif', '**/*.jpg', '**/*.png', '**/*.svg'],
					dest: publicThemeDir
				}]
			},
			js: {
				files: [{
					expand: true,
					cwd: srcThemeDir,
					src: ['**/*.js'],
					dest: publicThemeDir
				}],
				options: {
					process: function ( content ) {
						console.log( content );
						content = content.replace( /pilauBreakpointLarge/g, breakpoints.large );
						content = content.replace( /pilauBreakpointMedium/g, breakpoints.medium );
						return content;
					}
				}
			},
			fonts: {
				files: [{
					expand: true,
					cwd: srcThemeDir,
					src: ['**/*.eot', '**/*.ttf', '**/*.woff'],
					dest: publicThemeDir
				}]
			},
			root: {
				files: [{
					expand: true,
					dot: true,
					cwd: srcDir,
					src: ['*'],
					dest: publicDir
				}]
			}
		},

		// Watch for changes
		watch: {
			styles: {
				files: [srcThemeDir + 'styles/*.scss'],
				tasks: ['styles_changed']
			},
			livereload: {
				files: [publicThemeDir + '**/*.css'],
				options: { livereload: true }
			},
			php: {
				files: [srcThemeDir + '**/*.php'],
				tasks: ['php_changed']
			},
			img: {
				files: [srcDir + '**/*.png', srcDir + '**/*.jpg', srcDir + '**/*.gif', srcDir + '**/*.svg' ],
				tasks: ['img_changed']
			},
			js: {
				files: [srcDir + '**/*.js'],
				tasks: ['js_changed']
			},
			fonts: {
				files: ['**/*.eot', '**/*.ttf', '**/*.woff'],
				tasks: ['fonts_changed']
			},
			root: {
				files:	[srcDir + '*.php', srcDir + '*.txt', srcDir + '.ht*', srcDir + '*.ico', srcDir + '*.xml', srcDir + '*.json' ],
				tasks:	['root_changed']
			}
		}

	});

};

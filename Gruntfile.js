module.exports = function(grunt) {

	grunt.initConfig({
		uglify : {
			my_target : {
				options : {
					sourceMap : true,
					sourceMapName : 'js/bolt.socialite.min.map',
					preserveComments : 'some'
				},
				files : {
					'js/bolt.socialite.min.js' : [
							'js/socialite.js',
							'js/extensions/socialite.bufferapp.js',
							'js/extensions/socialite.github.js',
							'js/extensions/socialite.pinterest.js',
							'js/bolt.socialite.load.js' ]
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.registerTask('default', [ 'uglify' ]);

};
module.exports = function(grunt) {

    grunt.initConfig({
        uglify: {
            base: {
                options: {
                    sourceMap: true,
                    sourceMapName: 'web/bolt.socialite.min.map',
                    preserveComments: 'some'
                },
                files: {
                    'bolt.socialite.min.js': [
                        'web/socialite.js',
                        'web/extensions/socialite.bufferapp.js',
                        'web/extensions/socialite.github.js',
                        'web/extensions/socialite.pinterest.js',
                        'web/bolt.socialite.load.js'
                    ]
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['uglify']);

};

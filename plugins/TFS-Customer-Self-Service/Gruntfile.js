module.exports = function( grunt ) {
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-sass');

    grunt.initConfig({
        watch: {
            watch_js_files: {
                files : ['assets/js/*.js'],
                tasks : ['uglify']
            },
            watch_sass_files: {
                files : ['assets/sass/**/*.scss'],
                tasks : ['sass']
            }
        },
        uglify: {
            target: {
                files: {
                    'assets/js/min/tfs-css-plugin-backend.min.js': ['assets/js/tfs-css-plugin-backend.js'],
                    'assets/js/min/tfs-css-plugin-frontend.min.js': ['assets/js/tfs-css-plugin-frontend.js'],
                    'assets/js/min/tfs-css-plugin-sitewide.min.js': ['assets/js/tfs-css-plugin-sitewide.js']
                }
            }

        },
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'assets/css/tfs-css-plugin-sitewide.css': 'assets/sass/tfs-css-plugin-sitewide.scss'
                }
            }
        }
    });

    grunt.registerTask('default','watch');
};
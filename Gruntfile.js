module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    makepot: {
        target: {
            options: {
                cwd: '',                          // Directory of files to internationalize.
                domainPath: '/languages',         // Where to save the POT file.
                potComments: '',                  // The copyright at the beginning of the POT file.
                potFilename: '',                  // Name of the POT file.
                potHeaders: {
                    poedit: true,                 // Includes common Poedit headers.
                    'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
                },                                // Headers to add to the generated POT file.
                processPot: null,                 // A callback function for manipulating the POT file.
                type: 'wp-plugin',                // Type of project (wp-plugin or wp-theme).
                updateTimestamp: true             // Whether the POT-Creation-Date should be updated without other changes.
            }
        }
    },

     wp_readme_to_markdown: {
        your_target: {
          files: {
            'readme.md': 'README.txt'
          },
        },
      },

    // watch: {
    //   grunt: { files: ['Gruntfile.js'] },
    // }

  });

  grunt.loadNpmTasks('grunt-wp-i18n');
  //grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-wp-readme-to-markdown');

  grunt.registerTask('build', ['makepot', 'wp_readme_to_markdown']);
  // grunt.registerTask('default', ['build','watch']);
  grunt.registerTask('default', ['build']);
};
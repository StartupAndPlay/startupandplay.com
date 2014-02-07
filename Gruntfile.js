'use strict';

module.exports = function (grunt) {

  // load all grunt tasks
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),
 
    uglify: {
      min: {
        files: {
          'public/wp-content/themes/startupandplay/js/main.js': ['public/wp-content/themes/startupandplay/js/src/libs/*.js','public/wp-content/themes/startupandplay/js/src/*.js']
        }
      }
    },
 
    compass: {
      dist: {
        options: {
          config: 'public/wp-content/themes/startupandplay/css/config.rb',
          sassDir: 'public/wp-content/themes/startupandplay/css/sass',
          imagesDir: 'public/wp-content/themes/startupandplay/img',
          cssDir: 'public/wp-content/themes/startupandplay/css',
          environment: 'production',
          outputStyle: 'compressed',
          force: true
        }
      }
    },

    imagemin: {
      dynamic: {
        files: [{
          expand: true,
          cwd: 'public/wp-content/themes/startupandplay/img/src',
          src: ['*.{png,jpg,gif}'],
          dest: 'public/wp-content/themes/startupandplay/img/'
        }]
      }
    },

    watch: {
      options: {
        livereload: true
      },
      scripts: {
        files: ['public/wp-content/themes/startupandplay/js/src/*.js','public/wp-content/themes/startupandplay/js/src/libs/*.js'],
        tasks: ['uglify']
      },
      styles: {
        files: ['public/wp-content/themes/startupandplay/css/**/*.{sass,scss}','public/wp-content/themes/startupandplay/img/ui/*.png'],
        tasks: ['compass']
      },
      images: {
        files: ['public/wp-content/themes/startupandplay/img/src/*.{png,jpg,gif}'],
        tasks: ['imagemin']
      }
    },
  });
 
  // Development task checks and concatenates JS, compiles SASS preserving comments and nesting, runs dev server, and starts watch
  grunt.registerTask('default', ['compass', 'uglify', 'imagemin', 'watch']);
 
 }

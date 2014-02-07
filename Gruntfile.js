'use strict';

module.exports = function (grunt) {

  // load all grunt tasks
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),
 
    uglify: {
      min: {
        files: {
          'public/content/themes/startupandplay/js/main.js': ['public/content/themes/startupandplay/js/src/libs/*.js','public/content/themes/startupandplay/js/src/*.js']
        }
      }
    },
 
    compass: {
      dist: {
        options: {
          config: 'public/content/themes/startupandplay/css/config.rb',
          sassDir: 'public/content/themes/startupandplay/css/sass',
          imagesDir: 'public/content/themes/startupandplay/img',
          cssDir: 'public/content/themes/startupandplay/css',
          environment: 'production',
          outputcss: 'compressed',
          force: true
        }
      }
    },

    imagemin: {
      dynamic: {
        files: [{
          expand: true,
          cwd: 'public/content/themes/startupandplay/img/src',
          src: ['*.{png,jpg,gif}'],
          dest: 'public/content/themes/startupandplay/img/'
        }]
      }
    },

    watch: {
      options: {
        livereload: true
      },
      scripts: {
        files: ['public/content/themes/startupandplay/js/src/*.js','public/content/themes/startupandplay/js/src/libs/*.js'],
        tasks: ['uglify']
      },
      csss: {
        files: ['public/content/themes/startupandplay/css/**/*.{sass,scss}','public/content/themes/startupandplay/img/ui/*.png'],
        tasks: ['compass']
      },
      images: {
        files: ['public/content/themes/startupandplay/img/src/*.{png,jpg,gif}'],
        tasks: ['imagemin']
      }
    },
  });
 
  // Development task checks and concatenates JS, compiles SASS preserving comments and nesting, runs dev server, and starts watch
  grunt.registerTask('default', ['compass', 'uglify', 'imagemin', 'watch']);
 
 }
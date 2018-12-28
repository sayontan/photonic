module.exports = function(grunt) {
	// Config
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		dirs: {
			src: '../include/scripts/front-end/src',
			dest: '../include/scripts/front-end/build'
		},

		vars: { },

		concat: {
			colorbox: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Colorbox.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-colorbox.js'
			},
			fancybox: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Fancybox.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-fancybox.js'
			},
			fancybox2: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Fancybox2.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-fancybox2.js'
			},
			fancybox3: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Fancybox3.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-fancybox3.js'
			},
			featherlight: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Featherlight.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-featherlight.js'
			},
/*
			fluidbox: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Fluidbox.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-fluidbox.js'
			},
*/
			galleria: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Galleria.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-galleria.js'
			},
			imagelightbox: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_ImageLightbox.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-imagelightbox.js'
			},
			lightcase: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Lightcase.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-lightcase.js'
			},
			lightgallery: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Lightgallery.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-lightgallery.js'
			},
			magnific: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Magnific.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-magnific.js'
			},
			none: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-none.js'
			},
			photoswipe: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_PhotoSwipe.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-photoswipe.js'
			},
			prettyphoto: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_PrettyPhoto.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-prettyphoto.js'
			},
			strip: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Strip.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-strip.js'
			},
			swipebox: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Swipebox.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-swipebox.js'
			},
			thickbox: {
				options: {
					process: true
				},
				src: [
					'<%= dirs.src %>/core/external.js',
					'<%= dirs.src %>/core/jq-start.tmpl',
					'<%= dirs.src %>/core/core.js',
					'<%= dirs.src %>/lightboxes/Lightbox.js',
					'<%= dirs.src %>/lightboxes/Lightbox_Thickbox.js',
					'<%= dirs.src %>/core/layouts.js',
					'<%= dirs.src %>/core/jq-end.tmpl'
				],
				dest: '<%= dirs.dest %>/photonic-thickbox.js'
			}
		}
	});

	// Load plugins
	var cwd = process.cwd();
	process.chdir('../../../../..');
	grunt.loadNpmTasks('grunt-contrib-concat');
//	grunt.loadNpmTasks('grunt-jquery-ready');
	process.chdir(cwd);

	// Tasks
	grunt.registerTask('default', [
		'concat:colorbox',
		'concat:fancybox',
		'concat:fancybox2',
		'concat:fancybox3',
		'concat:featherlight',
//		'concat:fluidbox',
		'concat:imagelightbox',
		'concat:galleria',
		'concat:lightcase',
		'concat:lightgallery',
		'concat:magnific',
		'concat:none',
		'concat:photoswipe',
		'concat:prettyphoto',
		'concat:strip',
		'concat:swipebox',
		'concat:thickbox'
	]);
};
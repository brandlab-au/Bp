<?php
// Theme css & js

function base_scripts_styles() {
	$in_footer = true;
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	wp_deregister_script( 'comment-reply' );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply', get_template_directory_uri() . '/js/comment-reply.js', '', '', $in_footer );
	}

	wp_enqueue_style( 'raleway', 'https://fonts.googleapis.com/css?family=Raleway:400,700', array() );
	wp_enqueue_style( 'garamond', 'https://fonts.googleapis.com/css?family=EB+Garamond', array() );

	// Loads JavaScript file with functionality specific.
	wp_enqueue_script( 'theme-bootstrap-script', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '' );
	wp_enqueue_script( 'backbone' );
	wp_enqueue_script( 'underscore' );
	wp_enqueue_style( 'dashicons' );

	// Loads JavaScript file with functionality specific.
	wp_enqueue_script( 'wp-util' );
	wp_enqueue_script( 'jquery.nanoscroller.min.js', get_template_directory_uri() . '/js/jquery.nanoscroller.min.js', array( 'jquery' ), '' );
	wp_enqueue_script( 'readmore', get_template_directory_uri() . '/js/readmore.min.js', array( 'jquery' ), '' );

	// OWL carousel
	wp_enqueue_script( 'owl.carousel.min.js', get_template_directory_uri() . '/js/owl.carousel.min.js', array( 'jquery' ), '', $in_footer );

	//Sticky
	wp_enqueue_script( 'jquery.sticky-kit.min.js', get_template_directory_uri() . '/js/jquery.sticky-kit.min.js', array( 'jquery' ), '', $in_footer );

	wp_enqueue_script( 'theme-script', get_template_directory_uri() . '/js/jquery.main.js', array( 'jquery' ), '', $in_footer );

	// Bootstrap
	wp_enqueue_style( 'theme-bootstrap', get_template_directory_uri() . '/css/bootstrap-extended.css', array() );

	// OWL carousel
	wp_enqueue_style( 'owl.carousel.css', get_template_directory_uri() . '/css/owl.carousel.css', array() );

	// Loads our main stylesheet.
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array() );
	
	// Custom CSS by Zakir
	//wp_enqueue_style( 'custom-css', get_template_directory_uri() . '/css/custom.css', array() );

	// Fonts
	wp_enqueue_style( 'theme-font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array() );

	// Implementation stylesheet.
//	wp_enqueue_style( 'theme-impl', get_template_directory_uri() . '/theme.css', array() );

	if ( is_singular( 'apartments' ) or is_singular( 'product' ) or is_singular( 'post' ) ) {
		if ( is_singular( 'apartments' ) ) {
			wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC_tlM99Dwa8BQcrAoYePX2peWQk7b40Q8', '', '', $in_footer );
		}
		wp_enqueue_script( 'sharethis', 'http://w.sharethis.com/button/buttons.js', '', '' );
		wp_enqueue_script( 'sharethis-code', get_template_directory_uri() . '/js/sharethis.js', array( 'sharethis' ), '' );
	}

	if ( is_page_template( 'pages/template-settings.php' ) ) {
		wp_enqueue_script( 'googleapis-places', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC_tlM99Dwa8BQcrAoYePX2peWQk7b40Q8&libraries=places' );

		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_register_style( 'jquery-ui-styles', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui-styles' );
	}

	if ( current_user_can( 'upload_files' ) ) {
		wp_enqueue_media();
	}
}

add_action( 'wp_enqueue_scripts', 'base_scripts_styles' );

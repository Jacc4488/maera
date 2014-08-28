<?php

/**
 * Enqueue scripts and stylesheets
 */
function shoestrap_scripts() {
	global $wp_customize;
	global $active_framework;

	// Get the stylesheet path and version
	$stylesheet_url = apply_filters( 'shoestrap/stylesheet/url', SHOESTRAP_ASSETS_URL . '/css/style.css' );
	$stylesheet_ver = apply_filters( 'shoestrap/stylesheet/ver', null );

	// Enqueue the theme's stylesheet
	wp_enqueue_style( 'shoestrap', $stylesheet_url, false, $stylesheet_ver );

	wp_enqueue_script( 'shoestrap-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20130115', true );

	// Enqueue Modernizr
	wp_register_script( 'modernizr', SHOESTRAP_ASSETS_URL . '/js/modernizr-2.7.0.min.js', false, null, false );
	wp_enqueue_script( 'modernizr' );

	// Enqueue fitvids
	wp_register_script( 'fitvids', SHOESTRAP_ASSETS_URL . '/js/jquery.fitvids.js',false, null, true  );
	wp_enqueue_script( 'fitvids' );


	// Enqueue jQuery
	wp_enqueue_script( 'jquery' );

	// If needed, add the comment-reply script.
	if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$caching = apply_filters( 'shoestrap/styles/caching', false );

	// If we're on the customizer, then set caching to false
	if ( isset( $wp_customize ) ) {
		$caching = false;
	}

	if ( ! $caching ) {

		// Get our styles using the shoestrap/styles filter
		$data = apply_filters( 'shoestrap/styles', null );

	} else {

		// Get the cached CSS from the database
		$cache = get_theme_mod( 'css_cache', '' );

		// If the transient does not exist, then create it.
		if ( $cache === false || empty( $cache ) || '' == $cache ) {
			// Get our styles using the shoestrap/styles filter
			$data = apply_filters( 'shoestrap/styles', null );
			// Set the transient for 24 hours.
			set_theme_mod( 'css_cache', $data );
		}

	}

	// Add the CSS inline.
	// See http://codex.wordpress.org/Function_Reference/wp_add_inline_style#Examples
	wp_add_inline_style( 'shoestrap', $data );

}
add_action( 'wp_enqueue_scripts', 'shoestrap_scripts', 100 );

/**
 * Reset the cache when saving the customizer
 */
function shoestrap_reset_style_cache_on_customizer_save() {

	remove_theme_mod( 'css_cache' );

}
add_action( 'customize_save_after', 'shoestrap_reset_style_cache_on_customizer_save' );

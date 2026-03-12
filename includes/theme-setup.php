<?php
/**
 * Theme setup and asset loading.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers theme supports.
 *
 * @return void
 */
function econopapi_theme_setup() {
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );

	add_editor_style( 'assets/css/front-page.css' );
}
add_action( 'after_setup_theme', 'econopapi_theme_setup' );

/**
 * Enqueues global and front page styles.
 *
 * @return void
 */
function econopapi_enqueue_styles() {
	wp_enqueue_style(
		'econopapi-theme-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-theme-css' ),
		ECONOPAPI_THEME_VERSION
	);

	wp_enqueue_style(
		'econopapi-header-style',
		get_stylesheet_directory_uri() . '/assets/css/header.css',
		array( 'econopapi-theme-style' ),
		ECONOPAPI_THEME_VERSION
	);

	wp_enqueue_script(
		'econopapi-header-menu',
		get_stylesheet_directory_uri() . '/assets/js/header-menu.js',
		array(),
		ECONOPAPI_THEME_VERSION,
		true
	);

	if ( is_front_page() ) {
		wp_enqueue_style(
			'econopapi-front-page-style',
			get_stylesheet_directory_uri() . '/assets/css/front-page.css',
			array( 'econopapi-theme-style' ),
			ECONOPAPI_THEME_VERSION
		);
	}

	if ( is_singular() && ! is_front_page() ) {
		wp_enqueue_style(
			'econopapi-singular-style',
			get_stylesheet_directory_uri() . '/assets/css/singular.css',
			array( 'econopapi-theme-style' ),
			ECONOPAPI_THEME_VERSION
		);
	}

	if ( is_single() ) {
		wp_enqueue_script(
			'econopapi-single-outline',
			get_stylesheet_directory_uri() . '/assets/js/single-outline.js',
			array(),
			ECONOPAPI_THEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'econopapi_enqueue_styles', 15 );

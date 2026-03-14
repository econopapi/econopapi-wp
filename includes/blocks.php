<?php
/**
 * Gutenberg blocks registration.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers all custom blocks for the theme.
 *
 * @return void
 */
function econopapi_register_blocks() {
	$hero_block_path = get_stylesheet_directory() . '/custom-blocks/hero';
	$profile_block_path = get_stylesheet_directory() . '/custom-blocks/profile-card';

	if ( file_exists( $hero_block_path . '/block.json' ) ) {
		register_block_type( $hero_block_path );
	}

	if ( file_exists( $profile_block_path . '/block.json' ) ) {
		register_block_type( $profile_block_path );
	}
}
add_action( 'init', 'econopapi_register_blocks' );

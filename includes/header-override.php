<?php
/**
 * Header override for Astra.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Replaces Astra default header markup with custom header template part.
 *
 * @return void
 */
function econopapi_override_astra_header() {
	if ( ! function_exists( 'astra_header_markup' ) ) {
		return;
	}

	remove_action( 'astra_header', 'astra_header_markup' );
	add_action( 'astra_header', 'econopapi_render_custom_header', 10 );
}
add_action( 'wp', 'econopapi_override_astra_header', 5 );

/**
 * Renders the custom header template.
 *
 * @return void
 */
function econopapi_render_custom_header() {
	get_template_part( 'template-parts/header/site', 'header' );
}

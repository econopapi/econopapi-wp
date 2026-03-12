<?php
/**
 * Theme customizer settings.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds custom settings to Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function econopapi_customize_register( $wp_customize ) {
	$wp_customize->add_setting(
		'econopapi_site_handle',
		array(
			'default'           => '@econopapi',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'econopapi_site_handle',
		array(
			'label'       => __( 'Usuario/Handle del sitio', 'econopapi-wp' ),
			'description' => __( 'Texto secundario mostrado debajo del nombre del sitio en el header.', 'econopapi-wp' ),
			'section'     => 'title_tagline',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'econopapi_dark_logo_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'econopapi_dark_logo_id',
			array(
				'label'       => __( 'Logo para modo oscuro', 'econopapi-wp' ),
				'description' => __( 'Logo alternativo usado cuando el sitio está en modo oscuro.', 'econopapi-wp' ),
				'section'     => 'title_tagline',
				'mime_type'   => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'econopapi_customize_register' );

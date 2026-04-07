<?php
/**
 * Custom footer implementation for Econopapi theme.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Removes Astra's default footer and registers custom footer.
 *
 * @return void
 */
function econopapi_setup_custom_footer() {
	// Remove all Astra footer actions
	remove_all_actions( 'astra_footer' );
	remove_all_actions( 'astra_footer_before' );
	remove_all_actions( 'astra_footer_after' );
	
	// Disable Astra's footer builder completely by overriding theme options
	add_filter( 'astra_theme_defaults', 'econopapi_disable_astra_footer' );
	
	// Add our custom footer
	add_action( 'astra_footer', 'econopapi_render_custom_footer' );
}
add_action( 'after_setup_theme', 'econopapi_setup_custom_footer', 5 );

/**
 * Disables Astra footer builder in theme defaults.
 *
 * @param array $defaults Theme defaults.
 * @return array Modified defaults.
 */
function econopapi_disable_astra_footer( $defaults ) {
	$defaults['footer-builder-layout'] = '';
	$defaults['footer-sml-layout'] = '';
	$defaults['footer-adv'] = 'disabled';
	return $defaults;
}

/**
 * Renders the custom footer template.
 *
 * @return void
 */
function econopapi_render_custom_footer() {
	get_template_part( 'template-parts/footer/footer' );
}

/**
 * Enqueues footer styles.
 *
 * @return void
 */
function econopapi_enqueue_footer_styles() {
	wp_enqueue_style(
		'econopapi-footer',
		get_stylesheet_directory_uri() . '/assets/css/footer.css',
		array( 'econopapi-theme-style' ),
		ECONOPAPI_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'econopapi_enqueue_footer_styles', 30 );

/**
 * Registers footer widget areas.
 *
 * @return void
 */
function econopapi_register_footer_widgets() {
	// Footer main widget area
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Principal', 'econopapi-wp' ),
			'id'            => 'footer-main',
			'description'   => esc_html__( 'Área de widgets principal del footer.', 'econopapi-wp' ),
			'before_widget' => '<div id="%1$s" class="eco-footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="eco-footer-widget__title">',
			'after_title'   => '</h3>',
		)
	);

	// Footer copyright area
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Copyright', 'econopapi-wp' ),
			'id'            => 'footer-copyright',
			'description'   => esc_html__( 'Área para texto de copyright y enlaces legales.', 'econopapi-wp' ),
			'before_widget' => '<div id="%1$s" class="eco-footer-copyright %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="screen-reader-text">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'econopapi_register_footer_widgets' );
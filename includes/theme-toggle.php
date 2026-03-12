<?php
/**
 * Theme mode toggle (light/dark) integration.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prints an early script to apply saved theme mode before painting.
 *
 * @return void
 */
function econopapi_print_theme_mode_bootstrap() {
	?>
	<script>
		( function () {
			var storageKey = 'econopapi-theme';
			var savedTheme = null;
			try {
				savedTheme = localStorage.getItem( storageKey );
			} catch ( error ) {
				savedTheme = null;
			}

			var prefersDark = window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
			var shouldUseDark = savedTheme === 'dark' || ( ! savedTheme && prefersDark );
			var body = document.body;

			if ( ! body ) {
				return;
			}

			if ( shouldUseDark ) {
				body.classList.add( 'theme-dark' );
				body.setAttribute( 'data-theme', 'dark' );
			} else {
				body.classList.remove( 'theme-dark' );
				body.setAttribute( 'data-theme', 'light' );
			}
		} )();
	</script>
	<?php
}
add_action( 'wp_body_open', 'econopapi_print_theme_mode_bootstrap', 1 );

/**
 * Enqueues assets for theme toggle.
 *
 * @return void
 */
function econopapi_enqueue_theme_toggle_assets() {
	wp_enqueue_style(
		'econopapi-theme-toggle',
		get_stylesheet_directory_uri() . '/assets/css/theme-toggle.css',
		array( 'econopapi-theme-style' ),
		ECONOPAPI_THEME_VERSION
	);

	wp_enqueue_script(
		'econopapi-theme-toggle',
		get_stylesheet_directory_uri() . '/assets/js/theme-toggle.js',
		array(),
		ECONOPAPI_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'econopapi_enqueue_theme_toggle_assets', 20 );

/**
 * Appends a toggle button in the primary menu.
 *
 * @param string $items Current menu HTML items.
 * @param object $args  Nav menu args.
 * @return string
 */
function econopapi_add_theme_toggle_to_primary_menu( $items, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}

	$button_html = '<li class="menu-item menu-item-type-custom econopapi-theme-toggle-item">';
	$button_html .= '<button type="button" class="eco-theme-toggle" aria-pressed="false" aria-label="Alternar modo oscuro" title="Alternar modo oscuro">';
	$button_html .= '<span class="eco-theme-toggle__track" aria-hidden="true"><span class="eco-theme-toggle__thumb"></span></span>';
	$button_html .= '<span class="eco-theme-toggle__text">Modo oscuro</span>';
	$button_html .= '</button></li>';

	return $items . $button_html;
}
add_filter( 'wp_nav_menu_items', 'econopapi_add_theme_toggle_to_primary_menu', 10, 2 );

<?php
/**
 * Econopapi theme bootstrap file.
 *
 * Mantiene únicamente el punto de entrada y carga modular de funcionalidades.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ECONOPAPI_THEME_VERSION' ) ) {
	define( 'ECONOPAPI_THEME_VERSION', '1.1.0' );
}

require_once get_stylesheet_directory() . '/includes/theme-setup.php';
require_once get_stylesheet_directory() . '/includes/blocks.php';
require_once get_stylesheet_directory() . '/includes/theme-toggle.php';
require_once get_stylesheet_directory() . '/includes/header-override.php';
require_once get_stylesheet_directory() . '/includes/customizer.php';
require_once get_stylesheet_directory() . '/includes/singular-helpers.php';
require_once get_stylesheet_directory() . '/includes/blog-archive.php';
require_once get_stylesheet_directory() . '/includes/projects.php';
require_once get_stylesheet_directory() . '/includes/footer.php';
require_once get_stylesheet_directory() . '/includes/schema.php';
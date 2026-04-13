<?php
/**
 * Helper functions for singular templates.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns estimated reading time in minutes.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function econopapi_get_reading_time( $post_id ) {
	$raw_content = get_post_field( 'post_content', $post_id );
	$word_count  = str_word_count( wp_strip_all_tags( (string) $raw_content ) );

	return max( 1, (int) ceil( $word_count / 220 ) );
}

/**
 * Stores generated outline by post ID for current request.
 *
 * @param int   $post_id Post ID.
 * @param array $outline Outline items.
 * @return void
 */
function econopapi_set_single_outline( $post_id, $outline ) {
	global $econopapi_single_outline_map;

	if ( ! is_array( $econopapi_single_outline_map ) ) {
		$econopapi_single_outline_map = array();
	}

	$econopapi_single_outline_map[ $post_id ] = $outline;
}

/**
 * Returns generated outline by post ID for current request.
 *
 * @param int $post_id Post ID.
 * @return array<int,array<string,string>>
 */
function econopapi_get_single_outline( $post_id ) {
	global $econopapi_single_outline_map;

	if ( is_array( $econopapi_single_outline_map ) && isset( $econopapi_single_outline_map[ $post_id ] ) ) {
		return $econopapi_single_outline_map[ $post_id ];
	}

	return array();
}

/**
 * Adds IDs to post H2 headings and stores an outline for single template sidebar.
 *
 * @param string $content Rendered post content.
 * @return string
 */
function econopapi_single_content_add_heading_ids( $content ) {
	if ( ! is_singular( array( 'post', 'project' ) ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return $content;
	}

	$outline = array();
	$used    = array();

	$updated_content = preg_replace_callback(
		'/<h2([^>]*)>(.*?)<\/h2>/is',
		static function ( $matches ) use ( &$outline, &$used ) {
			$attrs = (string) $matches[1];
			$inner = (string) $matches[2];
			$text  = trim( wp_strip_all_tags( html_entity_decode( $inner, ENT_QUOTES, 'UTF-8' ) ) );

			if ( '' === $text ) {
				return $matches[0];
			}

			$base = sanitize_title( $text );
			if ( '' === $base ) {
				$base = 'section';
			}

			$base_id = 'eco-section-' . $base;
			$id      = $base_id;
			$index   = 2;

			while ( in_array( $id, $used, true ) ) {
				$id = $base_id . '-' . $index;
				$index++;
			}

			$used[] = $id;

			$attrs = preg_replace( '/\s+id=("|\').*?\1/i', '', $attrs );
			$attrs = trim( (string) $attrs );
			$attrs = ( '' !== $attrs ? ' ' . $attrs : '' ) . ' id="' . esc_attr( $id ) . '"';

			$outline[] = array(
				'id'    => $id,
				'label' => $text,
			);

			return '<h2' . $attrs . '>' . $inner . '</h2>';
		},
		$content
	);

	econopapi_set_single_outline( $post_id, array_slice( $outline, 0, 8 ) );

	return is_string( $updated_content ) ? $updated_content : $content;
}
add_filter( 'the_content', 'econopapi_single_content_add_heading_ids', 20 );

/**
 * Gets a URL-like post meta and validates it.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @return string
 */
function econopapi_get_post_meta_url( $post_id, $meta_key ) {
	$meta_value = (string) get_post_meta( $post_id, $meta_key, true );
	$meta_value = trim( $meta_value );

	if ( '' === $meta_value ) {
		return '';
	}

	$sanitized = esc_url_raw( $meta_value );
	return is_string( $sanitized ) ? $sanitized : '';
}

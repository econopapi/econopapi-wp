<?php
/**
 * Schema.org markup helpers.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns Person schema data for the front page.
 *
 * @return array<string, mixed>
 */
function econopapi_get_person_schema_data() {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Person',
		'name'     => 'Daniel Limón',
		'url'      => 'https://econopapi.com',
		'image'    => 'https://econopapi.com/dani.jpg',
		'jobTitle' => array(
			'Economista',
			'Desarrollador de software',
		),
		'sameAs'   => array(
			'https://github.com/econopapi',
			'https://youtube.com/@econopapi',
			'https://linkedin.com/in/dlimon2',
			'https://instagram.com/econopapi',
		),
	);

	/**
	 * Filters the Person schema payload before output.
	 *
	 * @param array<string, mixed> $schema Schema payload.
	 */
	return (array) apply_filters( 'econopapi_person_schema_data', $schema );
}

/**
 * Prints Person schema as JSON-LD in the page head.
 *
 * @return void
 */
function econopapi_print_person_schema() {
	if ( ! is_front_page() ) {
		return;
	}

	$schema = econopapi_get_person_schema_data();
	if ( empty( $schema ) ) {
		return;
	}

	$schema_json = wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( ! is_string( $schema_json ) || '' === $schema_json ) {
		return;
	}

	// JSON-LD script tag output for search engines.
	echo '<script type="application/ld+json">' . $schema_json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'econopapi_print_person_schema', 20 );

/**
 * Returns Article schema data for a blog post.
 *
 * @param int $post_id Optional post ID. Falls back to queried object.
 * @return array<string, mixed>
 */
function econopapi_get_article_schema_data( $post_id = 0 ) {
	$post_id = $post_id > 0 ? (int) $post_id : (int) get_queried_object_id();
	$post    = $post_id > 0 ? get_post( $post_id ) : null;

	if ( ! $post instanceof WP_Post || 'post' !== $post->post_type ) {
		return array();
	}

	$author_id   = (int) $post->post_author;
	$author_name = (string) get_the_author_meta( 'display_name', $author_id );

	if ( '' === $author_name ) {
		$author_name = 'Daniel Limón';
	}

	$author_url = (string) get_author_posts_url( $author_id );
	if ( '' === $author_url ) {
		$author_url = home_url( '/' );
	}

	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
		'datePublished'    => get_the_date( 'c', $post ),
		'dateModified'     => get_the_modified_date( 'c', $post ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => $author_name,
			'url'   => $author_url,
		),
		'publisher'        => array(
			'@type' => 'Person',
			'name'  => 'Daniel Limón',
			'url'   => 'https://econopapi.com',
		),
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => get_permalink( $post ),
		),
	);

	$thumbnail_url = get_the_post_thumbnail_url( $post, 'large' );
	if ( is_string( $thumbnail_url ) && '' !== $thumbnail_url ) {
		$schema['image'] = $thumbnail_url;
	}

	/**
	 * Filters the Article schema payload before output.
	 *
	 * @param array<string, mixed> $schema  Schema payload.
	 * @param WP_Post              $post    Current post object.
	 */
	return (array) apply_filters( 'econopapi_article_schema_data', $schema, $post );
}

/**
 * Prints Article schema as JSON-LD in the page head.
 *
 * @return void
 */
function econopapi_print_article_schema() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$schema = econopapi_get_article_schema_data();
	if ( empty( $schema ) ) {
		return;
	}

	$schema_json = wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( ! is_string( $schema_json ) || '' === $schema_json ) {
		return;
	}

	// JSON-LD script tag output for search engines.
	echo '<script type="application/ld+json">' . $schema_json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'econopapi_print_article_schema', 20 );

/**
 * Returns BreadcrumbList schema data for singular content and project archive.
 *
 * @return array<string, mixed>
 */
function econopapi_get_breadcrumb_schema_data() {
	if ( ! is_singular() && ! is_post_type_archive( 'project' ) ) {
		return array();
	}

	$item_list = array(
		array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => __( 'Inicio', 'econopapi-wp' ),
			'item'     => home_url( '/' ),
		),
	);

	if ( is_singular( 'post' ) ) {
		$posts_page_id = (int) get_option( 'page_for_posts' );
		$posts_label   = __( 'Blog', 'econopapi-wp' );
		$posts_url     = home_url( '/' );

		if ( $posts_page_id > 0 ) {
			$posts_title = get_the_title( $posts_page_id );
			$posts_label = is_string( $posts_title ) && '' !== $posts_title ? $posts_title : $posts_label;
			$posts_link  = get_permalink( $posts_page_id );
			$posts_url   = is_string( $posts_link ) && '' !== $posts_link ? $posts_link : $posts_url;
		} elseif ( function_exists( 'econopapi_get_blog_archive_url' ) ) {
			$archive_url = econopapi_get_blog_archive_url();
			if ( is_string( $archive_url ) && '' !== $archive_url ) {
				$posts_url = $archive_url;
			}
		}

		$item_list[] = array(
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => $posts_label,
			'item'     => $posts_url,
		);
	} elseif ( is_singular( 'project' ) || is_post_type_archive( 'project' ) ) {
		$archive_link = get_post_type_archive_link( 'project' );
		$post_type    = get_post_type_object( 'project' );
		$archive_name = $post_type && isset( $post_type->labels->name ) ? (string) $post_type->labels->name : __( 'Proyectos', 'econopapi-wp' );

		if ( is_string( $archive_link ) && '' !== $archive_link ) {
			$item_list[] = array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => $archive_name,
				'item'     => $archive_link,
			);
		}
	}

	if ( is_page() ) {
		$post_id     = (int) get_queried_object_id();
		$ancestors   = get_post_ancestors( $post_id );
		$ancestors   = array_reverse( array_map( 'intval', $ancestors ) );
		$position    = count( $item_list ) + 1;

		foreach ( $ancestors as $ancestor_id ) {
			$ancestor_title = get_the_title( $ancestor_id );
			$ancestor_link  = get_permalink( $ancestor_id );

			if ( ! is_string( $ancestor_title ) || '' === $ancestor_title || ! is_string( $ancestor_link ) || '' === $ancestor_link ) {
				continue;
			}

			$item_list[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => $ancestor_title,
				'item'     => $ancestor_link,
			);
			$position++;
		}
	}

	if ( is_singular() ) {
		$current_title = get_the_title();
		$current_link  = get_permalink();

		if ( is_string( $current_title ) && '' !== $current_title && is_string( $current_link ) && '' !== $current_link ) {
			$item_list[] = array(
				'@type'    => 'ListItem',
				'position' => count( $item_list ) + 1,
				'name'     => wp_strip_all_tags( $current_title ),
				'item'     => $current_link,
			);
		}
	}

	if ( count( $item_list ) < 2 ) {
		return array();
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $item_list,
	);

	/**
	 * Filters the BreadcrumbList schema payload before output.
	 *
	 * @param array<string, mixed> $schema Schema payload.
	 */
	return (array) apply_filters( 'econopapi_breadcrumb_schema_data', $schema );
}

/**
 * Prints BreadcrumbList schema as JSON-LD in the page head.
 *
 * @return void
 */
function econopapi_print_breadcrumb_schema() {
	$schema = econopapi_get_breadcrumb_schema_data();
	if ( empty( $schema ) ) {
		return;
	}

	$schema_json = wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( ! is_string( $schema_json ) || '' === $schema_json ) {
		return;
	}

	// JSON-LD script tag output for search engines.
	echo '<script type="application/ld+json">' . $schema_json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'econopapi_print_breadcrumb_schema', 20 );

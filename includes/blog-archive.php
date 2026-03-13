<?php
/**
 * Blog archive helpers and assets.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the canonical base URL for the blog archive.
 *
 * @return string
 */
function econopapi_get_blog_archive_url() {
	$page_for_posts = (int) get_option( 'page_for_posts' );

	if ( $page_for_posts > 0 ) {
		$permalink = get_permalink( $page_for_posts );
		if ( is_string( $permalink ) && '' !== $permalink ) {
			return $permalink;
		}
	}

	return home_url( '/' );
}

/**
 * Returns the active category slug in blog archive context.
 *
 * @return string
 */
function econopapi_get_blog_active_category_slug() {
	if ( is_category() ) {
		$queried = get_queried_object();
		if ( $queried instanceof WP_Term ) {
			return (string) $queried->slug;
		}
	}

	$category_name = (string) get_query_var( 'category_name' );
	return sanitize_title( $category_name );
}

/**
 * Builds a URL for a category filter chip.
 *
 * @param string $slug Category slug.
 * @return string
 */
function econopapi_get_blog_filter_url( $slug ) {
	$base_url = econopapi_get_blog_archive_url();
	$slug     = sanitize_title( (string) $slug );

	if ( '' === $slug ) {
		return remove_query_arg( 'category_name', $base_url );
	}

	return add_query_arg( 'category_name', $slug, remove_query_arg( 'category_name', $base_url ) );
}

/**
 * Renders a single blog archive card for the current loop item.
 *
 * @return string
 */
function econopapi_render_blog_archive_card() {
	$categories     = get_the_category();
	$first_category = ! empty( $categories ) ? $categories[0] : null;

	ob_start();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'eco-blog-card' ); ?> role="listitem">
		<div class="eco-blog-card__inner">
			<?php if ( $first_category ) : ?>
				<p class="eco-blog-card__category"><?php echo esc_html( $first_category->name ); ?></p>
			<?php endif; ?>

			<h2 class="eco-blog-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>

			<?php if ( has_excerpt() ) : ?>
				<p class="eco-blog-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php else : ?>
				<p class="eco-blog-card__excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 16 ) ); ?></p>
			<?php endif; ?>

			<p class="eco-blog-card__meta">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
					<?php echo esc_html( wp_date( 'M Y', get_post_timestamp() ) ); ?>
				</time>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( sprintf( __( '%d min', 'econopapi-wp' ), econopapi_get_reading_time( (int) get_the_ID() ) ) ); ?></span>
			</p>
		</div>
	</article>
	<?php

	return (string) ob_get_clean();
}

/**
 * Handles blog archive load-more requests.
 *
 * @return void
 */
function econopapi_handle_blog_archive_load_more() {
	check_ajax_referer( 'econopapi_blog_archive_load_more', 'nonce' );

	$page          = isset( $_POST['page'] ) ? (int) wp_unslash( $_POST['page'] ) : 1;
	$category_slug = isset( $_POST['category'] ) ? sanitize_title( (string) wp_unslash( $_POST['category'] ) ) : '';
	$page          = max( 1, $page );

	$query_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'posts_per_page'      => (int) get_option( 'posts_per_page' ),
		'paged'               => $page,
	);

	if ( '' !== $category_slug ) {
		$query_args['category_name'] = $category_slug;
	}

	$posts_query = new WP_Query( $query_args );
	$html        = '';

	if ( $posts_query->have_posts() ) {
		while ( $posts_query->have_posts() ) {
			$posts_query->the_post();
			$html .= econopapi_render_blog_archive_card();
		}
	}

	wp_reset_postdata();

	$max_pages = max( 1, (int) $posts_query->max_num_pages );
	$has_more  = $page < $max_pages;
	$next_url  = '';

	if ( $has_more ) {
		$next_url = econopapi_get_blog_filter_url( $category_slug );
		$next_url = add_query_arg( 'paged', (string) ( $page + 1 ), $next_url );
	}

	wp_send_json_success(
		array(
			'html'     => $html,
			'hasMore'  => $has_more,
			'nextPage' => $page + 1,
			'nextUrl'  => $next_url,
		)
	);
}
add_action( 'wp_ajax_econopapi_blog_archive_load_more', 'econopapi_handle_blog_archive_load_more' );
add_action( 'wp_ajax_nopriv_econopapi_blog_archive_load_more', 'econopapi_handle_blog_archive_load_more' );

/**
 * Enqueues blog archive stylesheet.
 *
 * @return void
 */
function econopapi_enqueue_blog_archive_assets() {
	if ( ! is_home() && ! is_category() ) {
		return;
	}

	wp_enqueue_style(
		'econopapi-blog-archive-style',
		get_stylesheet_directory_uri() . '/assets/css/blog-archive.css',
		array( 'econopapi-theme-style' ),
		ECONOPAPI_THEME_VERSION
	);

	wp_enqueue_script(
		'econopapi-blog-archive-load-more',
		get_stylesheet_directory_uri() . '/assets/js/blog-archive.js',
		array(),
		ECONOPAPI_THEME_VERSION,
		true
	);

	$max_pages = isset( $GLOBALS['wp_query'] ) && $GLOBALS['wp_query'] instanceof WP_Query
		? max( 1, (int) $GLOBALS['wp_query']->max_num_pages )
		: 1;

	wp_localize_script(
		'econopapi-blog-archive-load-more',
		'econopapiBlogArchive',
		array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'econopapi_blog_archive_load_more' ),
			'category' => econopapi_get_blog_active_category_slug(),
			'labels'   => array(
				'loading' => __( 'Cargando publicaciones...', 'econopapi-wp' ),
				'error'   => __( 'No se pudo cargar. Abriendo la siguiente página...', 'econopapi-wp' ),
				'loaded'  => __( 'Se cargaron más publicaciones.', 'econopapi-wp' ),
				'noMore'  => __( 'No hay más publicaciones por cargar.', 'econopapi-wp' ),
			),
			'maxPages' => $max_pages,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'econopapi_enqueue_blog_archive_assets', 15 );

/**
 * Normalizes the main blog archive query pagination behavior.
 *
 * @param WP_Query $query Query instance.
 * @return void
 */
function econopapi_configure_blog_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_home() && ! $query->is_category() ) {
		return;
	}

	$posts_per_page = (int) get_option( 'posts_per_page' );
	if ( $posts_per_page <= 0 ) {
		$posts_per_page = 10;
	}

	$query->set( 'posts_per_page', $posts_per_page );
	$query->set( 'ignore_sticky_posts', 1 );
}
add_action( 'pre_get_posts', 'econopapi_configure_blog_archive_query' );
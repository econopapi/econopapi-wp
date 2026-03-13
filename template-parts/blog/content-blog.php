<?php
/**
 * Blog archive content template.
 *
 * @package EconopapiWP
 */

$active_category_slug = econopapi_get_blog_active_category_slug();
$categories           = get_categories(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
	)
);

$current_page = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

$posts_per_page = (int) get_option( 'posts_per_page' );
if ( $posts_per_page <= 0 ) {
	$posts_per_page = 10;
}

$archive_query_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => $posts_per_page,
	'paged'               => $current_page,
);

if ( '' !== $active_category_slug ) {
	$archive_query_args['category_name'] = $active_category_slug;
}

$archive_query = new WP_Query( $archive_query_args );
$max_num_pages = max( 1, (int) $archive_query->max_num_pages );
$next_page_url = '';

if ( $current_page < $max_num_pages ) {
	$next_page_url = econopapi_get_blog_filter_url( $active_category_slug );
	$next_page_url = add_query_arg( 'paged', (string) ( $current_page + 1 ), $next_page_url );
}
?>
<main id="primary" class="site-main eco-blog-archive" role="main">
	<section class="eco-blog-archive__hero" aria-labelledby="eco-blog-archive-title">
		<div class="eco-container">
			<p class="eco-blog-archive__eyebrow"><?php esc_html_e( 'Blog', 'econopapi-wp' ); ?></p>
			<h1 id="eco-blog-archive-title" class="eco-blog-archive__title"><?php esc_html_e( 'Publicaciones', 'econopapi-wp' ); ?></h1>
			<p class="eco-blog-archive__description">
				<?php esc_html_e( 'Economía, datos y desarrollo — desde una perspectiva que cruza los tres.', 'econopapi-wp' ); ?>
			</p>

			<nav class="eco-blog-filters" aria-label="<?php esc_attr_e( 'Filtrar publicaciones por categoría', 'econopapi-wp' ); ?>">
				<a
					class="eco-blog-filter-chip <?php echo '' === $active_category_slug ? 'is-active' : ''; ?>"
					href="<?php echo esc_url( econopapi_get_blog_filter_url( '' ) ); ?>"
				>
					<?php esc_html_e( 'Todos', 'econopapi-wp' ); ?>
				</a>

				<?php foreach ( $categories as $category ) : ?>
					<a
						class="eco-blog-filter-chip <?php echo $active_category_slug === $category->slug ? 'is-active' : ''; ?>"
						href="<?php echo esc_url( econopapi_get_blog_filter_url( $category->slug ) ); ?>"
					>
						<?php echo esc_html( $category->name ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</div>
	</section>

	<section class="eco-blog-archive__posts" aria-label="<?php esc_attr_e( 'Listado de publicaciones', 'econopapi-wp' ); ?>">
		<?php if ( $archive_query->have_posts() ) : ?>
			<div id="eco-blog-grid" class="eco-blog-grid" role="list">
				<?php
				while ( $archive_query->have_posts() ) :
					$archive_query->the_post();
					echo econopapi_render_blog_archive_card(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				endwhile;
				?>
			</div>

			<?php if ( $next_page_url ) : ?>
				<div class="eco-blog-pagination">
					<a
						class="eco-blog-load-more"
						href="<?php echo esc_url( $next_page_url ); ?>"
						data-current-page="<?php echo esc_attr( $current_page ); ?>"
						data-max-pages="<?php echo esc_attr( $max_num_pages ); ?>"
						data-category="<?php echo esc_attr( $active_category_slug ); ?>"
						data-default-label="<?php esc_attr_e( 'Cargar más publicaciones', 'econopapi-wp' ); ?>"
						data-loading-label="<?php esc_attr_e( 'Cargando publicaciones...', 'econopapi-wp' ); ?>"
						aria-controls="eco-blog-grid"
					>
						<?php esc_html_e( 'Cargar más publicaciones', 'econopapi-wp' ); ?>
					</a>
					<p class="eco-blog-pagination-status screen-reader-text" aria-live="polite"></p>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="eco-container">
				<p class="eco-blog-empty-state"><?php esc_html_e( 'Aún no hay publicaciones disponibles.', 'econopapi-wp' ); ?></p>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</section>
</main>
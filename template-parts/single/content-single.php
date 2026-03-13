<?php
/**
 * Single post content template.
 *
 * @package EconopapiWP
 */
?>
<main id="primary" class="site-main eco-single" role="main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			$post_id      = get_the_ID();
			$reading_time = econopapi_get_reading_time( $post_id );
			$repo_url     = econopapi_get_post_meta_url( $post_id, 'econopapi_repo_url' );
			$demo_url     = econopapi_get_post_meta_url( $post_id, 'econopapi_demo_url' );
			$categories   = get_the_category();
			$category     = ! empty( $categories ) ? $categories[0] : null;
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'eco-single-article' ); ?>>
				<header class="eco-single-hero">
					<div class="eco-container">
						<div class="eco-single-meta-row">
							<?php if ( $category ) : ?>
								<a class="eco-single-category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
									<?php echo esc_html( $category->name ); ?>
								</a>
							<?php endif; ?>
							<span class="eco-single-meta"><?php echo esc_html( wp_date( 'M Y', get_post_timestamp() ) . ' · ' . $reading_time . ' min de lectura' ); ?></span>
						</div>

						<h1 class="eco-single-title"><?php the_title(); ?></h1>

						<?php if ( has_excerpt() ) : ?>
							<p class="eco-single-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
					</div>
				</header>

				<div class="eco-reading-bar" data-reading-bar>
					<div class="eco-container eco-reading-bar__inner">
						<p class="eco-reading-bar__title"><?php the_title(); ?></p>
					</div>
					<span class="eco-reading-bar__progress" data-reading-progress></span>
				</div>

				<div class="eco-single-layout eco-container">
					<div class="eco-single-content">
						<?php the_content(); ?>
						<?php $outline = econopapi_get_single_outline( $post_id ); ?>

						<?php
						the_tags(
							'<div class="eco-single-tags">',
							'',
							'</div>'
						);
						?>
					</div>

					<?php
					get_template_part(
						'template-parts/single/sidebar',
						'single',
						array(
							'outline'  => $outline,
							'repo_url' => $repo_url,
							'demo_url' => $demo_url,
						)
					);
					?>
				</div>
			</article>

			<section class="eco-more-posts" aria-labelledby="eco-more-posts-title">
				<div class="eco-container">
					<h2 id="eco-more-posts-title" class="eco-section-title"><?php esc_html_e( 'Más publicaciones', 'econopapi-wp' ); ?></h2>
					<?php
					$more_posts = new WP_Query(
						array(
							'post_type'      => 'post',
							'posts_per_page' => 2,
							'post__not_in'   => array( $post_id ),
						)
					);
					?>
					<?php if ( $more_posts->have_posts() ) : ?>
						<ul class="eco-more-posts-list" role="list">
							<?php while ( $more_posts->have_posts() ) : $more_posts->the_post(); ?>
								<li class="eco-more-post-item">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( wp_date( 'M Y', get_post_timestamp() ) ); ?></time>
								</li>
							<?php endwhile; ?>
						</ul>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</section>
			<?php
		endwhile;
	endif;
	?>
</main>

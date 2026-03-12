<?php
/**
 * Front page latest posts section.
 *
 * @package EconopapiWP
 */

$latest_posts_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
	)
);
?>
<section class="eco-latest-posts" aria-labelledby="eco-latest-posts-title">
	<div class="eco-container">
		<h2 id="eco-latest-posts-title" class="eco-section-title"><?php esc_html_e( 'Últimas publicaciones', 'econopapi-wp' ); ?></h2>

		<?php if ( $latest_posts_query->have_posts() ) : ?>
			<ul class="eco-post-list" role="list">
				<?php
				while ( $latest_posts_query->have_posts() ) :
					$latest_posts_query->the_post();
					$categories     = get_the_category();
					$first_category = ! empty( $categories ) ? $categories[0] : null;
					?>
					<li class="eco-post-item">
						<div class="eco-post-main">
							<h3 class="eco-post-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<?php if ( $first_category ) : ?>
								<a class="eco-post-category" href="<?php echo esc_url( get_category_link( $first_category->term_id ) ); ?>">
									<?php echo esc_html( $first_category->name ); ?>
								</a>
							<?php endif; ?>
						</div>
						<time class="eco-post-date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
							<?php echo esc_html( wp_date( 'M Y', get_post_timestamp() ) ); ?>
						</time>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<p class="eco-empty-state"><?php esc_html_e( 'Aún no hay publicaciones disponibles.', 'econopapi-wp' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php wp_reset_postdata(); ?>

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

$latest_projects_query = new WP_Query(
	array(
		'post_type'           => 'project',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
	)
);
?>
<section class="eco-latest-posts" aria-labelledby="eco-latest-content-title">
	<div class="eco-container">
		<h2 id="eco-latest-content-title" class="eco-section-title"><?php esc_html_e( 'Reciente', 'econopapi-wp' ); ?></h2>

		<div class="eco-latest-grid">
			<section class="eco-latest-column" aria-labelledby="eco-latest-posts-title">
				<h3 id="eco-latest-posts-title" class="eco-subsection-title"><?php esc_html_e( 'Últimas publicaciones', 'econopapi-wp' ); ?></h3>

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
									<h4 class="eco-post-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h4>
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
			</section>

			<section class="eco-latest-column" aria-labelledby="eco-latest-projects-title">
				<h3 id="eco-latest-projects-title" class="eco-subsection-title"><?php esc_html_e( 'Proyectos recientes', 'econopapi-wp' ); ?></h3>

				<?php if ( $latest_projects_query->have_posts() ) : ?>
					<ul class="eco-project-list" role="list">
						<?php
						while ( $latest_projects_query->have_posts() ) :
							$latest_projects_query->the_post();
							$project_meta = function_exists( 'econopapi_get_project_meta' )
								? econopapi_get_project_meta( (int) get_the_ID() )
								: array();
							$status_label = isset( $project_meta['status_label'] ) ? (string) $project_meta['status_label'] : '';
							?>
							<li class="eco-project-item">
								<div class="eco-project-main">
									<h4 class="eco-project-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h4>
									<?php if ( '' !== $status_label ) : ?>
										<span class="eco-project-status"><?php echo esc_html( $status_label ); ?></span>
									<?php endif; ?>
								</div>
								<?php if ( has_excerpt() ) : ?>
									<p class="eco-project-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
								<?php else : ?>
									<p class="eco-project-excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 16 ) ); ?></p>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					</ul>
				<?php else : ?>
					<p class="eco-empty-state"><?php esc_html_e( 'Aún no hay proyectos publicados.', 'econopapi-wp' ); ?></p>
				<?php endif; ?>
			</section>
		</div>

	</div>
</section>
<?php wp_reset_postdata(); ?>

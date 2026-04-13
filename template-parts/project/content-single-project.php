<?php
/**
 * Single project content template.
 *
 * @package EconopapiWP
 */
?>
<main id="primary" class="site-main eco-single eco-single--project" role="main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			$post_id         = get_the_ID();
			$reading_time    = econopapi_get_reading_time( $post_id );
			$project_meta    = econopapi_get_project_meta( $post_id );
			$project_url     = $project_meta['project_url'];
			$repo_url        = $project_meta['repo_url'];
			$status_label    = $project_meta['status_label'];
			$project_link    = econopapi_get_project_link_display_data( $project_url, 'project' );
			$repo_link       = econopapi_get_project_link_display_data( $repo_url, 'repo' );
			$project_outline = array();
			$meta_items      = array(
				array(
					'label'  => __( 'Estatus', 'econopapi-wp' ),
					'value'  => $status_label,
					'type'   => 'badge',
					'layout' => 'inline',
				),
			);

			if ( '' !== $project_url ) {
				$meta_items[] = array(
					'label'    => __( 'Proyecto', 'econopapi-wp' ),
					'value'    => $project_link['label'],
					'url'      => $project_url,
					'icon'     => $project_link['icon'],
					'external' => true,
				);
			}

			if ( '' !== $repo_url ) {
				$meta_items[] = array(
					'label'    => __( 'Repositorio', 'econopapi-wp' ),
					'value'    => $repo_link['label'],
					'url'      => $repo_url,
					'icon'     => $repo_link['icon'],
					'external' => true,
				);
			}
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'eco-single-article eco-single-article--project' ); ?>>
				<header class="eco-single-hero">
					<div class="eco-container">
						<div class="eco-single-meta-row">
							<span class="eco-single-category eco-single-category--status eco-single-category--project-status">
								<?php echo esc_html( $status_label ); ?>
							</span>
							<span class="eco-single-meta"><?php echo esc_html( wp_date( 'M Y', get_post_timestamp() ) . ' · ' . $reading_time . ' min de lectura' ); ?></span>
						</div>

						<h1 class="eco-single-title"><?php the_title(); ?></h1>

						<?php if ( has_excerpt() ) : ?>
							<p class="eco-single-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>

						<?php if ( '' !== $project_url || '' !== $repo_url ) : ?>
							<div class="eco-project-actions" aria-label="<?php esc_attr_e( 'Enlaces del proyecto', 'econopapi-wp' ); ?>">
								<?php if ( '' !== $project_url ) : ?>
									<a class="eco-project-action eco-project-action--primary" href="<?php echo esc_url( $project_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Ver proyecto', 'econopapi-wp' ); ?></a>
								<?php endif; ?>

								<?php if ( '' !== $repo_url ) : ?>
									<a class="eco-project-action eco-project-action--secondary" href="<?php echo esc_url( $repo_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Ver código', 'econopapi-wp' ); ?></a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</header>

				<div class="eco-reading-bar" data-reading-bar>
					<div class="eco-container eco-reading-bar__inner">
						<div class="eco-reading-bar__content eco-reading-bar__content--project">
							<p class="eco-reading-bar__title"><?php the_title(); ?></p>
							<div class="eco-reading-bar__project-meta" aria-label="<?php esc_attr_e( 'Resumen del proyecto', 'econopapi-wp' ); ?>">
								<span class="eco-reading-bar__project-status"><?php echo esc_html( $status_label ); ?></span>
								<?php if ( '' !== $project_url ) : ?>
									<a class="eco-reading-bar__project-link" href="<?php echo esc_url( $project_url ); ?>" target="_blank" rel="noopener noreferrer">
										<span class="eco-reading-bar__project-link-icon" aria-hidden="true"><?php echo econopapi_get_project_meta_icon_svg( $project_link['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
										<span><?php echo esc_html( $project_link['label'] ); ?></span>
									</a>
								<?php endif; ?>
								<?php if ( '' !== $repo_url ) : ?>
									<a class="eco-reading-bar__project-link" href="<?php echo esc_url( $repo_url ); ?>" target="_blank" rel="noopener noreferrer">
										<span class="eco-reading-bar__project-link-icon" aria-hidden="true"><?php echo econopapi_get_project_meta_icon_svg( $repo_link['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
										<span><?php echo esc_html( $repo_link['label'] ); ?></span>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<span class="eco-reading-bar__progress" data-reading-progress></span>
				</div>

				<div class="eco-single-layout eco-container">
					<div class="eco-single-content eco-single-content--project">
						<?php the_content(); ?>
						<?php $project_outline = econopapi_get_single_outline( $post_id ); ?>

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
							'outline'            => $project_outline,
							'outline_title'      => __( 'Índice del proyecto', 'econopapi-wp' ),
							'sidebar_label'      => __( 'Información del proyecto', 'econopapi-wp' ),
							'meta_section_title' => __( 'Ficha del proyecto', 'econopapi-wp' ),
							'meta_items'         => $meta_items,
						)
					);
					?>
				</div>
			</article>

			<section class="eco-more-posts eco-more-posts--split" aria-labelledby="eco-project-more-content-title">
				<div class="eco-container">
					<h2 id="eco-project-more-content-title" class="eco-section-title"><?php esc_html_e( 'Sigue explorando', 'econopapi-wp' ); ?></h2>
					<div class="eco-more-content-grid">
						<div class="eco-more-content-column">
							<h3 class="eco-more-content-heading"><?php esc_html_e( 'Más publicaciones', 'econopapi-wp' ); ?></h3>
							<?php $related_posts = econopapi_get_related_blog_posts( $post_id, 2 ); ?>
							<?php if ( $related_posts->have_posts() ) : ?>
								<ul class="eco-more-posts-list" role="list">
									<?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
										<li class="eco-more-post-item">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( wp_date( 'M Y', get_post_timestamp() ) ); ?></time>
										</li>
									<?php endwhile; ?>
								</ul>
							<?php else : ?>
								<p class="eco-more-empty-state"><?php esc_html_e( 'Aún no hay publicaciones relacionadas.', 'econopapi-wp' ); ?></p>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>
						</div>

						<div class="eco-more-content-column">
							<h3 class="eco-more-content-heading"><?php esc_html_e( 'Otros proyectos', 'econopapi-wp' ); ?></h3>
							<?php $related_projects = econopapi_get_related_projects( $post_id, 2 ); ?>
							<?php if ( $related_projects->have_posts() ) : ?>
								<ul class="eco-more-posts-list" role="list">
									<?php while ( $related_projects->have_posts() ) : $related_projects->the_post(); ?>
										<?php $related_project_meta = econopapi_get_project_meta( get_the_ID() ); ?>
										<li class="eco-more-post-item eco-more-post-item--project">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											<span class="eco-more-post-meta"><?php echo esc_html( $related_project_meta['status_label'] ); ?></span>
										</li>
									<?php endwhile; ?>
								</ul>
							<?php else : ?>
								<p class="eco-more-empty-state"><?php esc_html_e( 'Aún no hay otros proyectos relacionados.', 'econopapi-wp' ); ?></p>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>
						</div>
					</div>
				</div>
			</section>
			<?php
		endwhile;
	endif;
	?>
</main>
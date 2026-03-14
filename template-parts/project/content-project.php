<?php
/**
 * Projects archive content template.
 *
 * @package EconopapiWP
 */
?>
<main id="primary" class="site-main eco-projects-archive" role="main">
	<section class="eco-projects-archive__hero" aria-labelledby="eco-projects-archive-title">
		<div class="eco-container">
			<p class="eco-projects-archive__eyebrow"><?php esc_html_e( 'Proyectos', 'econopapi-wp' ); ?></p>
			<h1 id="eco-projects-archive-title" class="eco-projects-archive__title"><?php esc_html_e( 'Cosas que construí', 'econopapi-wp' ); ?></h1>
			<p class="eco-projects-archive__description">
				<?php esc_html_e( 'Herramientas en producción, experimentos y trabajo open source.', 'econopapi-wp' ); ?>
			</p>
		</div>
	</section>

	<section class="eco-projects-archive__list" aria-label="<?php esc_attr_e( 'Listado de proyectos', 'econopapi-wp' ); ?>">
		<div class="eco-container">
			<?php if ( have_posts() ) : ?>
				<div class="eco-projects-grid" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						echo econopapi_render_project_archive_card(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					endwhile;
					?>
				</div>
			<?php else : ?>
				<p class="eco-projects-empty-state"><?php esc_html_e( 'Aún no hay proyectos publicados.', 'econopapi-wp' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

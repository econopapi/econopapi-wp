<?php
/**
 * Single page content template.
 *
 * @package EconopapiWP
 */
?>
<main id="primary" class="site-main eco-page" role="main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article id="page-<?php the_ID(); ?>" <?php post_class( 'eco-page-article' ); ?>>
				<header class="eco-page-hero">
					<div class="eco-container">
						<h1 class="eco-page-title"><?php the_title(); ?></h1>
						<?php if ( has_excerpt() ) : ?>
							<p class="eco-page-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
					</div>
				</header>

				<div class="eco-page-content eco-container">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
	endif;
	?>
</main>

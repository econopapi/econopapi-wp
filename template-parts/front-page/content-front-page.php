<?php
/**
 * Front page content wrapper.
 *
 * @package EconopapiWP
 */
?>
<main id="primary" class="site-main econopapi-front-page" role="main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			$content = (string) get_post_field( 'post_content', get_the_ID() );

			if ( ! empty( trim( wp_strip_all_tags( $content ) ) ) || has_blocks( $content ) ) {
				the_content();
			} else {
				get_template_part( 'template-parts/front-page/sections/hero' );
				get_template_part( 'template-parts/front-page/sections/stack' );
				get_template_part( 'template-parts/front-page/sections/latest-posts' );
			}
		endwhile;
	endif;
	?>
</main>

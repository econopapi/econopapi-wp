<?php
/**
 * Custom footer template part.
 *
 * @package EconopapiWP
 */
?>
<footer class="eco-site-footer" role="contentinfo">
	<div class="eco-site-footer__inner eco-container">
		
		<?php if ( is_active_sidebar( 'footer-main' ) ) : ?>
			<div class="eco-footer-main">
				<div class="eco-footer-main__grid">
					<?php dynamic_sidebar( 'footer-main' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="eco-footer-bottom">
			<div class="eco-footer-bottom__left">
				<?php if ( is_active_sidebar( 'footer-copyright' ) ) : ?>
					<?php dynamic_sidebar( 'footer-copyright' ); ?>
				<?php else : ?>
					<p class="eco-footer-copyright__text">
						&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. 
						<?php esc_html_e( 'Hecho con ❤️ en México.', 'econopapi-wp' ); ?>
					</p>
				<?php endif; ?>
			</div>

			<div class="eco-footer-bottom__right">
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<nav class="eco-footer-nav" aria-label="<?php esc_attr_e( 'Enlaces del footer', 'econopapi-wp' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'menu_class'     => 'eco-footer-nav__list',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				<?php endif; ?>

				<div class="eco-footer-social">
					<?php get_template_part( 'template-parts/footer/social-links' ); ?>
				</div>
			</div>
		</div>

	</div>
</footer>
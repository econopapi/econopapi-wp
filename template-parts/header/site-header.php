<?php
/**
 * Custom site header template part.
 *
 * @package EconopapiWP
 */

$custom_logo_id = (int) get_theme_mod( 'custom_logo' );
$dark_logo_id   = (int) get_theme_mod( 'econopapi_dark_logo_id', 0 );
$site_name      = get_bloginfo( 'name' );
$site_handle    = get_theme_mod( 'econopapi_site_handle', '@econopapi' );
?>
<header class="eco-site-header" role="banner">
	<div class="eco-site-header__inner eco-container">
		<div class="eco-brand">
			<a class="eco-brand__link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $site_name ); ?>">
				<span class="eco-brand__logo" aria-hidden="true">
					<?php if ( $custom_logo_id ) : ?>
						<?php echo wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'eco-logo eco-logo--light' ) ); ?>
						<?php if ( $dark_logo_id ) : ?>
							<?php echo wp_get_attachment_image( $dark_logo_id, 'full', false, array( 'class' => 'eco-logo eco-logo--dark' ) ); ?>
						<?php endif; ?>
					<?php else : ?>
						<span class="eco-logo-placeholder"></span>
					<?php endif; ?>
				</span>
				<span class="eco-brand__meta">
					<span class="eco-brand__name"><?php echo esc_html( $site_name ); ?></span>
					<span class="eco-brand__handle"><?php echo esc_html( $site_handle ); ?></span>
				</span>
			</a>
		</div>

		<nav class="eco-main-nav" aria-label="<?php esc_attr_e( 'Menú principal', 'econopapi-wp' ); ?>">
			<div class="eco-main-nav__panel" id="eco-main-nav-panel">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_id'        => 'eco-primary-menu',
						'menu_class'     => 'eco-nav-list',
						'fallback_cb'    => false,
					)
				);
				?>
			</div>

			<div class="eco-main-nav__controls">
				<button type="button" class="eco-menu-toggle" aria-controls="eco-main-nav-panel" aria-expanded="false" aria-label="Abrir menú" title="Abrir menú">
					<span class="eco-menu-toggle__icon" aria-hidden="true"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Abrir menú principal', 'econopapi-wp' ); ?></span>
				</button>

				<button type="button" class="eco-theme-toggle" aria-pressed="false" aria-label="Alternar modo oscuro" title="Alternar modo oscuro">
					<span class="eco-theme-toggle__icon eco-theme-toggle__icon--sun" aria-hidden="true">
						<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
							<circle cx="12" cy="12" r="4"></circle>
							<path d="M12 2v2.5M12 19.5V22M4.93 4.93l1.77 1.77M17.3 17.3l1.77 1.77M2 12h2.5M19.5 12H22M4.93 19.07l1.77-1.77M17.3 6.7l1.77-1.77"></path>
						</svg>
					</span>
					<span class="eco-theme-toggle__icon eco-theme-toggle__icon--moon" aria-hidden="true">
						<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
							<path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5z"></path>
						</svg>
					</span>
					<span class="screen-reader-text"><?php esc_html_e( 'Alternar modo oscuro', 'econopapi-wp' ); ?></span>
				</button>
			</div>
		</nav>
	</div>
</header>

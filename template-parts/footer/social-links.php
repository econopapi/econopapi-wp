<?php
/**
 * Social links template part for footer.
 *
 * @package EconopapiWP
 */

$social_links = array(
	'twitter'   => array(
		'label' => esc_html__( 'Twitter', 'econopapi-wp' ),
		'url'   => get_theme_mod( 'econopapi_social_twitter', 'https://twitter.com/econopapi' ),
		'icon'  => 'twitter',
	),
	'linkedin'  => array(
		'label' => esc_html__( 'LinkedIn', 'econopapi-wp' ),
		'url'   => get_theme_mod( 'econopapi_social_linkedin', 'https://linkedin.com/in/econopapi' ),
		'icon'  => 'linkedin',
	),
	'github'    => array(
		'label' => esc_html__( 'GitHub', 'econopapi-wp' ),
		'url'   => get_theme_mod( 'econopapi_social_github', 'https://github.com/econopapi' ),
		'icon'  => 'github',
	),
	'instagram' => array(
		'label' => esc_html__( 'Instagram', 'econopapi-wp' ),
		'url'   => get_theme_mod( 'econopapi_social_instagram', 'https://instagram.com/econopapi' ),
		'icon'  => 'instagram',
	),
);

// Filter out empty social links
$active_social_links = array_filter(
	$social_links,
	function( $link ) {
		return ! empty( $link['url'] );
	}
);

if ( empty( $active_social_links ) ) {
	return;
}
?>
<ul class="eco-social-links" aria-label="<?php esc_attr_e( 'Redes sociales', 'econopapi-wp' ); ?>">
	<?php foreach ( $active_social_links as $platform => $link ) : ?>
		<li class="eco-social-links__item">
			<a 
				href="<?php echo esc_url( $link['url'] ); ?>" 
				class="eco-social-links__link"
				target="_blank"
				rel="noopener noreferrer"
				aria-label="<?php echo esc_attr( $link['label'] ); ?>"
			>
				<span class="eco-social-links__icon eco-icon--<?php echo esc_attr( $link['icon'] ); ?>" aria-hidden="true"></span>
				<span class="screen-reader-text"><?php echo esc_html( $link['label'] ); ?></span>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
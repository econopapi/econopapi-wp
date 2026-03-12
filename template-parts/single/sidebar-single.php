<?php
/**
 * Single post sidebar.
 *
 * @package EconopapiWP
 */

$outline  = isset( $args['outline'] ) && is_array( $args['outline'] ) ? $args['outline'] : array();
$repo_url = isset( $args['repo_url'] ) ? (string) $args['repo_url'] : '';
$demo_url = isset( $args['demo_url'] ) ? (string) $args['demo_url'] : '';
?>
<aside class="eco-single-sidebar" aria-label="<?php esc_attr_e( 'Información del post', 'econopapi-wp' ); ?>">
	<div class="eco-single-side-card">
		<?php if ( ! empty( $outline ) ) : ?>
			<h2 class="eco-side-title"><?php esc_html_e( 'En este post', 'econopapi-wp' ); ?></h2>
			<ul class="eco-side-list" role="list">
				<?php foreach ( $outline as $outline_item ) : ?>
					<li>
						<a class="eco-side-link" href="#<?php echo esc_attr( $outline_item['id'] ); ?>"><?php echo esc_html( $outline_item['label'] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $repo_url ) : ?>
			<div class="eco-side-block">
				<h3 class="eco-side-subtitle"><?php esc_html_e( 'Repo', 'econopapi-wp' ); ?></h3>
				<a href="<?php echo esc_url( $repo_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( wp_parse_url( $repo_url, PHP_URL_HOST ) ? wp_parse_url( $repo_url, PHP_URL_HOST ) : $repo_url ); ?></a>
			</div>
		<?php endif; ?>

		<?php if ( $demo_url ) : ?>
			<div class="eco-side-block">
				<h3 class="eco-side-subtitle"><?php esc_html_e( 'Demo', 'econopapi-wp' ); ?></h3>
				<a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( wp_parse_url( $demo_url, PHP_URL_HOST ) ? wp_parse_url( $demo_url, PHP_URL_HOST ) : $demo_url ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</aside>

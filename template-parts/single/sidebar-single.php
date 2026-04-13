<?php
/**
 * Single post sidebar.
 *
 * @package EconopapiWP
 */

$outline            = isset( $args['outline'] ) && is_array( $args['outline'] ) ? $args['outline'] : array();
$repo_url           = isset( $args['repo_url'] ) ? (string) $args['repo_url'] : '';
$demo_url           = isset( $args['demo_url'] ) ? (string) $args['demo_url'] : '';
$meta_items         = isset( $args['meta_items'] ) && is_array( $args['meta_items'] ) ? $args['meta_items'] : array();
$meta_section_title = isset( $args['meta_section_title'] ) ? (string) $args['meta_section_title'] : '';
$outline_title      = isset( $args['outline_title'] ) ? (string) $args['outline_title'] : __( 'En este post', 'econopapi-wp' );
$sidebar_label      = isset( $args['sidebar_label'] ) ? (string) $args['sidebar_label'] : __( 'Información del contenido', 'econopapi-wp' );
$meta_after_outline = ! empty( $args['meta_after_outline'] );

if ( empty( $meta_items ) ) {
	if ( $repo_url ) {
		$meta_items[] = array(
			'label'    => __( 'Repo', 'econopapi-wp' ),
			'value'    => wp_parse_url( $repo_url, PHP_URL_HOST ) ? wp_parse_url( $repo_url, PHP_URL_HOST ) : $repo_url,
			'url'      => $repo_url,
			'external' => true,
		);
	}

	if ( $demo_url ) {
		$meta_items[] = array(
			'label'    => __( 'Demo', 'econopapi-wp' ),
			'value'    => wp_parse_url( $demo_url, PHP_URL_HOST ) ? wp_parse_url( $demo_url, PHP_URL_HOST ) : $demo_url,
			'url'      => $demo_url,
			'external' => true,
		);
	}
}

if ( empty( $outline ) && empty( $meta_items ) ) {
	return;
}
?>
<aside class="eco-single-sidebar" aria-label="<?php echo esc_attr( $sidebar_label ); ?>">
	<div class="eco-single-side-card">
		<?php
		$render_meta_items = static function ( $items, $section_title ) {
			if ( empty( $items ) ) {
				return;
			}
			?>
			<div class="eco-side-block eco-side-block--meta">
				<?php if ( '' !== $section_title ) : ?>
					<h2 class="eco-side-title"><?php echo esc_html( $section_title ); ?></h2>
				<?php endif; ?>
				<ul class="eco-side-meta-list" role="list">
					<?php foreach ( $items as $item ) : ?>
						<?php
						$label    = isset( $item['label'] ) ? (string) $item['label'] : '';
						$value    = isset( $item['value'] ) ? (string) $item['value'] : '';
						$url      = isset( $item['url'] ) ? (string) $item['url'] : '';
						$external = ! empty( $item['external'] );
						$type     = isset( $item['type'] ) ? (string) $item['type'] : '';
						?>
						<?php if ( '' !== $label && '' !== $value ) : ?>
							<li class="eco-side-meta-item">
								<span class="eco-side-subtitle"><?php echo esc_html( $label ); ?></span>
								<?php if ( '' !== $url ) : ?>
									<a class="eco-side-meta-link<?php echo 'badge' === $type ? ' eco-side-meta-link--badge' : ''; ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $value ); ?></a>
								<?php else : ?>
									<span class="eco-side-meta-value<?php echo 'badge' === $type ? ' eco-side-meta-value--badge' : ''; ?>"><?php echo esc_html( $value ); ?></span>
								<?php endif; ?>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
		};
		?>

		<?php if ( $meta_after_outline ) : ?>
			<?php if ( ! empty( $outline ) ) : ?>
				<h2 class="eco-side-title"><?php echo esc_html( $outline_title ); ?></h2>
				<ul class="eco-side-list" role="list">
					<?php foreach ( $outline as $outline_item ) : ?>
						<li>
							<a class="eco-side-link" href="#<?php echo esc_attr( $outline_item['id'] ); ?>"><?php echo esc_html( $outline_item['label'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php $render_meta_items( $meta_items, $meta_section_title ); ?>
		<?php else : ?>
			<?php $render_meta_items( $meta_items, $meta_section_title ); ?>

			<?php if ( ! empty( $outline ) ) : ?>
				<div class="eco-side-block eco-side-block--outline">
					<h2 class="eco-side-title"><?php echo esc_html( $outline_title ); ?></h2>
					<ul class="eco-side-list" role="list">
						<?php foreach ( $outline as $outline_item ) : ?>
							<li>
								<a class="eco-side-link" href="#<?php echo esc_attr( $outline_item['id'] ); ?>"><?php echo esc_html( $outline_item['label'] ); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</aside>

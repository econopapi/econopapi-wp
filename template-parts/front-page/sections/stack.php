<?php
/**
 * Front page stack section.
 *
 * @package EconopapiWP
 */

$stack_items = array( 'Python', 'PHP', 'PostgreSQL', 'Linux', 'JavaScript' );
?>
<section class="eco-stack" aria-labelledby="eco-stack-title">
	<div class="eco-container">
		<h2 id="eco-stack-title" class="eco-section-title"><?php esc_html_e( 'Stack', 'econopapi-wp' ); ?></h2>
		<ul class="eco-pill-list" role="list">
			<?php foreach ( $stack_items as $item ) : ?>
				<li class="eco-pill"><?php echo esc_html( $item ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

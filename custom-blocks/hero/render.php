<?php
/**
 * Render callback for Econopapi Hero block.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'tagline'              => 'ECONOMISTA · DESARROLLADOR',
	'title'                => 'Datos, código y economía — desde México.',
	'description'          => 'Escribo sobre el cruce entre tecnología, datos y economía. Construyo herramientas con Python, PHP y todo lo que tenga sentido.',
	'primaryButtonLabel'   => 'Leer el blog',
	'primaryButtonUrl'     => '/blog',
	'secondaryButtonLabel' => 'Ver proyectos',
	'secondaryButtonUrl'   => '/proyectos',
);

$attributes = wp_parse_args( $attributes, $defaults );

$tagline              = sanitize_text_field( $attributes['tagline'] );
$title                = sanitize_text_field( $attributes['title'] );
$description          = sanitize_textarea_field( $attributes['description'] );
$primary_button_label = sanitize_text_field( $attributes['primaryButtonLabel'] );
$primary_button_url   = esc_url( $attributes['primaryButtonUrl'] );
$secondary_label      = sanitize_text_field( $attributes['secondaryButtonLabel'] );
$secondary_url        = esc_url( $attributes['secondaryButtonUrl'] );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'eco-hero-block' ) );
?>
<section <?php echo wp_kses_data( $wrapper_attributes ); ?> aria-labelledby="eco-hero-title">
	<div class="eco-hero">
		<div class="eco-container eco-hero-content">
			<?php if ( $tagline ) : ?>
				<p class="eco-hero-tagline"><?php echo esc_html( $tagline ); ?></p>
			<?php endif; ?>

			<?php if ( $title ) : ?>
				<h1 id="eco-hero-title" class="eco-hero-title"><?php echo esc_html( $title ); ?></h1>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<p class="eco-hero-description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<div class="eco-hero-actions">
				<?php if ( $primary_button_label ) : ?>
					<a class="eco-btn eco-btn-primary" href="<?php echo esc_url( $primary_button_url ? $primary_button_url : '#' ); ?>">
						<?php echo esc_html( $primary_button_label ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $secondary_label ) : ?>
					<a class="eco-btn eco-btn-secondary" href="<?php echo esc_url( $secondary_url ? $secondary_url : '#' ); ?>">
						<?php echo esc_html( $secondary_label ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

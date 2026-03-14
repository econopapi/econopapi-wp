<?php
/**
 * Render callback for Econopapi Profile Card block.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'fullName'        => 'Daniel Limón',
	'username'        => '@econopapi',
	'role'            => 'Economista · Desarrollador',
	'cardVariant'     => 'gradient',
	'locationLabel'   => 'Basado en',
	'location'        => 'México',
	'showEmail'       => true,
	'email'           => '',
	'showGithub'      => true,
	'githubUrl'       => '',
	'githubLabel'     => 'GitHub',
	'showLinkedin'    => true,
	'linkedinUrl'     => '',
	'linkedinLabel'   => 'LinkedIn',
	'showYoutube'     => true,
	'youtubeUrl'      => '',
	'youtubeLabel'    => 'YouTube',
	'avatarUrl'       => '',
	'avatarAlt'       => '',
	'avatarInitials'  => 'DL',
	'showGlassEffect' => true,
);

$attributes = wp_parse_args( $attributes, $defaults );

$allowed_variants = array( 'minimal', 'gradient', 'neon-soft' );
$card_variant     = sanitize_key( $attributes['cardVariant'] );

if ( ! in_array( $card_variant, $allowed_variants, true ) ) {
	$card_variant = 'gradient';
}

$full_name       = sanitize_text_field( $attributes['fullName'] );
$username        = sanitize_text_field( $attributes['username'] );
$role            = sanitize_text_field( $attributes['role'] );
$location_label  = sanitize_text_field( $attributes['locationLabel'] );
$location        = sanitize_text_field( $attributes['location'] );
$show_email      = ! empty( $attributes['showEmail'] );
$email           = sanitize_email( $attributes['email'] );
$show_github     = ! empty( $attributes['showGithub'] );
$github_url      = esc_url( $attributes['githubUrl'] );
$github_label    = sanitize_text_field( $attributes['githubLabel'] );
$show_linkedin   = ! empty( $attributes['showLinkedin'] );
$linkedin_url    = esc_url( $attributes['linkedinUrl'] );
$linkedin_label  = sanitize_text_field( $attributes['linkedinLabel'] );
$show_youtube    = ! empty( $attributes['showYoutube'] );
$youtube_url     = esc_url( $attributes['youtubeUrl'] );
$youtube_label   = sanitize_text_field( $attributes['youtubeLabel'] );
$avatar_url      = esc_url( $attributes['avatarUrl'] );
$avatar_alt      = sanitize_text_field( $attributes['avatarAlt'] );
$avatar_initials = sanitize_text_field( strtoupper( mb_substr( (string) $attributes['avatarInitials'], 0, 3 ) ) );
$is_glass        = ! empty( $attributes['showGlassEffect'] );

$card_classes = 'eco-profile-card is-variant-' . $card_variant;

if ( $is_glass ) {
	$card_classes .= ' is-glass';
}

$has_contact = ( $show_email && $email )
	|| ( $show_github && $github_url )
	|| ( $show_linkedin && $linkedin_url )
	|| ( $show_youtube && $youtube_url );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'eco-profile-card-block',
	)
);
?>
<section <?php echo wp_kses_data( $wrapper_attributes ); ?> aria-label="<?php esc_attr_e( 'Tarjeta de perfil', 'econopapi-wp' ); ?>">
	<article class="<?php echo esc_attr( $card_classes ); ?>">
		<div class="eco-profile-card__halo" aria-hidden="true"></div>
		<div class="eco-profile-card__avatar-wrap">
			<?php if ( $avatar_url ) : ?>
				<img
					src="<?php echo esc_url( $avatar_url ); ?>"
					alt="<?php echo esc_attr( $avatar_alt ? $avatar_alt : $full_name ); ?>"
					class="eco-profile-card__avatar-image"
				/>
			<?php else : ?>
				<span class="eco-profile-card__avatar-fallback" aria-hidden="true"><?php echo esc_html( $avatar_initials ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( $full_name ) : ?>
			<h3 class="eco-profile-card__name"><?php echo esc_html( $full_name ); ?></h3>
		<?php endif; ?>

		<?php if ( $username ) : ?>
			<p class="eco-profile-card__username"><?php echo esc_html( $username ); ?></p>
		<?php endif; ?>

		<?php if ( $role ) : ?>
			<p class="eco-profile-card__role"><?php echo esc_html( $role ); ?></p>
		<?php endif; ?>

		<?php if ( $has_contact ) : ?>
			<div class="eco-profile-card__contact">
			<?php if ( $show_email && $email ) : ?>
				<p class="eco-profile-card__line">
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</p>
			<?php endif; ?>

			<?php if ( $show_github && $github_url ) : ?>
				<p class="eco-profile-card__line eco-profile-card__line--link">
					<a href="<?php echo esc_url( $github_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $github_label ? $github_label : $github_url ); ?>
					</a>
				</p>
			<?php endif; ?>

			<?php if ( $show_linkedin && $linkedin_url ) : ?>
				<p class="eco-profile-card__line eco-profile-card__line--link">
					<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $linkedin_label ? $linkedin_label : $linkedin_url ); ?>
					</a>
				</p>
			<?php endif; ?>

			<?php if ( $show_youtube && $youtube_url ) : ?>
				<p class="eco-profile-card__line eco-profile-card__line--link">
					<a href="<?php echo esc_url( $youtube_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $youtube_label ? $youtube_label : $youtube_url ); ?>
					</a>
				</p>
			<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $location ) : ?>
			<div class="eco-profile-card__location">
				<p class="eco-profile-card__location-label"><?php echo esc_html( $location_label ); ?></p>
				<p class="eco-profile-card__location-value"><?php echo esc_html( $location ); ?></p>
			</div>
		<?php endif; ?>
	</article>
</section>

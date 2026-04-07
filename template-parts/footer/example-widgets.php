<?php
/**
 * Example footer widgets for demonstration.
 *
 * @package EconopapiWP
 */

// This file shows example widget content that can be used in the footer.
// To use these examples, copy the HTML into WordPress widgets.
?>

<!-- Example 1: About Section -->
<div class="eco-footer-widget">
	<h3 class="eco-footer-widget__title">Sobre Econopapi</h3>
	<p>Análisis de datos, desarrollo de software y economía aplicada desde México. Explorando la intersección entre tecnología y ciencias sociales.</p>
	<p><a href="<?php echo esc_url( home_url( '/sobre/' ) ); ?>">Conoce más →</a></p>
</div>

<!-- Example 2: Quick Links -->
<div class="eco-footer-widget">
	<h3 class="eco-footer-widget__title">Enlaces Rápidos</h3>
	<ul>
		<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
		<li><a href="<?php echo esc_url( home_url( '/proyectos/' ) ); ?>">Proyectos</a></li>
		<li><a href="<?php echo esc_url( home_url( '/recursos/' ) ); ?>">Recursos</a></li>
		<li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contacto</a></li>
	</ul>
</div>

<!-- Example 3: Categories -->
<div class="eco-footer-widget">
	<h3 class="eco-footer-widget__title">Categorías</h3>
	<ul>
		<li><a href="<?php echo esc_url( home_url( '/categoria/datos/' ) ); ?>">Análisis de Datos</a></li>
		<li><a href="<?php echo esc_url( home_url( '/categoria/economia/' ) ); ?>">Economía</a></li>
		<li><a href="<?php echo esc_url( home_url( '/categoria/desarrollo/' ) ); ?>">Desarrollo Web</a></li>
		<li><a href="<?php echo esc_url( home_url( '/categoria/opinion/' ) ); ?>">Opinión</a></li>
	</ul>
</div>

<!-- Example 4: Newsletter -->
<div class="eco-footer-widget">
	<h3 class="eco-footer-widget__title">Newsletter</h3>
	<p>Recibe análisis exclusivos y actualizaciones directamente en tu correo.</p>
	<form class="eco-newsletter-form" method="post">
		<div class="eco-newsletter-form__group">
			<input type="email" placeholder="tu@email.com" required aria-label="Dirección de email">
			<button type="submit" class="eco-button eco-button--small">Suscribirse</button>
		</div>
		<p class="eco-newsletter-form__note"><small>Sin spam. Puedes darte de baja en cualquier momento.</small></p>
	</form>
</div>

<!-- Example Copyright Text -->
<div class="eco-footer-copyright">
	<p class="eco-footer-copyright__text">
		&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. 
		<?php esc_html_e( 'Todos los derechos reservados.', 'econopapi-wp' ); ?>
		<br>
		<a href="<?php echo esc_url( home_url( '/privacidad/' ) ); ?>">Política de Privacidad</a> · 
		<a href="<?php echo esc_url( home_url( '/terminos/' ) ); ?>">Términos de Uso</a>
	</p>
</div>
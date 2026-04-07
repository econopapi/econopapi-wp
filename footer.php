<?php
/**
 * Custom footer template for Econopapi theme.
 * Completely replaces Astra's footer.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
	</div><!-- #content -->
	
	<?php
	// Our custom footer
	get_template_part( 'template-parts/footer/footer' );
	?>
	
	</div><!-- #page -->
<?php
	astra_body_bottom();
	wp_footer();
?>
	</body>
</html>
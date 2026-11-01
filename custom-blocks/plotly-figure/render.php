<?php
/**
 * Server render for the Econopapi Plotly Figure block.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$source_id = isset( $attributes['sourceId'] ) ? absint( $attributes['sourceId'] ) : 0;
$manifest  = econopapi_theme_plotly_read_manifest( $source_id );

if ( is_wp_error( $manifest ) ) {
	if ( current_user_can( 'edit_posts' ) ) {
		$message = $source_id
			? $manifest->get_error_message()
			: __( 'Selecciona o sube un manifiesto JSON desde el panel del bloque.', 'econopapi-wp' );

		printf(
			'<div %1$s><p>%2$s</p></div>',
			wp_kses_data( get_block_wrapper_attributes( array( 'class' => 'eco-plotly eco-plotly--empty' ) ) ),
			esc_html( $message )
		);
	}
	return;
}

$meta_defaults = array(
	'title'       => '',
	'kicker'      => '',
	'description' => '',
	'alt'         => '',
	'unit'        => '',
	'sources'     => array(),
	'methodology' => array(),
	'analysis'    => array(),
	'generatedAt' => '',
	'note'        => '',
);
$meta          = wp_parse_args( $manifest['meta'], $meta_defaults );
$methodology   = wp_parse_args( is_array( $meta['methodology'] ) ? $meta['methodology'] : array(), array( 'label' => '', 'url' => '' ) );
$analysis      = wp_parse_args(
	is_array( $meta['analysis'] ) ? $meta['analysis'] : array(),
	array( 'repository' => '', 'commit' => '', 'path' => '' )
);

$text_override = static function ( $key, $fallback ) use ( $attributes ) {
	if ( isset( $attributes[ $key ] ) && is_string( $attributes[ $key ] ) && '' !== trim( $attributes[ $key ] ) ) {
		return $attributes[ $key ];
	}
	return is_string( $fallback ) ? $fallback : '';
};

$title       = sanitize_text_field( $text_override( 'title', $meta['title'] ) );
$kicker      = sanitize_text_field( $text_override( 'kicker', $meta['kicker'] ) );
$description = sanitize_textarea_field( $text_override( 'description', $meta['description'] ) );
$alt         = sanitize_text_field( $text_override( 'alt', $meta['alt'] ) );
$unit        = sanitize_text_field( $text_override( 'unit', $meta['unit'] ) );
$note        = sanitize_textarea_field( $text_override( 'note', $meta['note'] ) );
$sources     = $meta['sources'];

$source_label = sanitize_text_field( $text_override( 'sourceLabel', '' ) );
$source_url   = esc_url_raw( $text_override( 'sourceUrl', '' ) );
if ( ( $source_label || $source_url ) && ! empty( $sources[0] ) ) {
	if ( $source_label ) {
		$sources[0]['label'] = $source_label;
	}
	if ( $source_url ) {
		$sources[0]['url'] = $source_url;
	}
}

$methodology_label = sanitize_text_field( $text_override( 'methodologyLabel', $methodology['label'] ) );
$methodology_url   = esc_url_raw( $text_override( 'methodologyUrl', $methodology['url'] ) );
$generated_at      = sanitize_text_field( $meta['generatedAt'] );
$height            = isset( $attributes['height'] ) ? absint( $attributes['height'] ) : 480;
$height            = min( 1000, max( 240, $height ) );
$table_open        = ! empty( $attributes['tableOpen'] );
$allow_download    = ! empty( $attributes['allowDownload'] );
$figure_id         = wp_unique_id( 'eco-plotly-' );
$description_id    = $figure_id . '-description';
$download_url      = wp_get_attachment_url( $source_id );
$table_caption     = ! empty( $manifest['table']['caption'] )
	? sanitize_text_field( $manifest['table']['caption'] )
	: __( 'Datos representados en la figura', 'econopapi-wp' );

$config = isset( $manifest['config'] ) && is_array( $manifest['config'] ) ? $manifest['config'] : array();
$payload = array(
	'schemaVersion' => 1,
	'figure'        => $manifest['figure'],
	'config'        => $config,
);
$payload_json = wp_json_encode( $payload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE );

$format_cell = static function ( $value ) {
	if ( null === $value ) {
		return '—';
	}
	if ( is_bool( $value ) ) {
		return $value ? __( 'Sí', 'econopapi-wp' ) : __( 'No', 'econopapi-wp' );
	}
	return (string) $value;
};
?>
<figure
	<?php echo wp_kses_data( get_block_wrapper_attributes( array( 'class' => 'eco-plotly' ) ) ); ?>
	data-eco-plotly
	aria-label="<?php echo esc_attr( $alt ); ?>"
>
	<script type="application/json" class="eco-plotly__payload"><?php echo $payload_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>

	<header class="eco-plotly__header">
		<?php if ( $kicker ) : ?>
			<p class="eco-plotly__kicker"><?php echo esc_html( $kicker ); ?></p>
		<?php endif; ?>
		<p class="eco-plotly__title"><?php echo esc_html( $title ); ?><span aria-hidden="true">.</span></p>
		<?php if ( $description ) : ?>
			<p class="eco-plotly__description" id="<?php echo esc_attr( $description_id ); ?>"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</header>

	<div class="eco-plotly__surface">
		<div
			class="eco-plotly__chart"
			style="height: <?php echo esc_attr( $height ); ?>px;"
			aria-hidden="true"
		></div>
	</div>

	<noscript>
		<p class="eco-plotly__notice"><?php esc_html_e( 'El gráfico interactivo necesita JavaScript. La tabla de datos y su procedencia siguen disponibles abajo.', 'econopapi-wp' ); ?></p>
	</noscript>
	<p class="eco-plotly__error" data-eco-plotly-error hidden></p>

	<figcaption class="eco-plotly__caption">
		<span class="eco-plotly__provenance">
			<strong><?php esc_html_e( 'Fuente:', 'econopapi-wp' ); ?></strong>
			<?php
			$source_output = array();
			foreach ( $sources as $source ) {
				$label = sanitize_text_field( $source['label'] );
				$url   = ! empty( $source['url'] ) ? esc_url( $source['url'] ) : '';
				if ( $url ) {
					$source_output[] = '<a href="' . $url . '">' . esc_html( $label ) . '</a>';
				} else {
					$source_output[] = esc_html( $label );
				}
			}
			echo wp_kses( implode( '; ', $source_output ), array( 'a' => array( 'href' => array() ) ) );
			?>
		</span>

		<?php if ( $unit ) : ?>
			<span class="eco-plotly__provenance"><strong><?php esc_html_e( 'Unidad:', 'econopapi-wp' ); ?></strong> <?php echo esc_html( $unit ); ?></span>
		<?php endif; ?>

		<?php if ( $methodology_label || $methodology_url ) : ?>
			<span class="eco-plotly__provenance">
				<strong><?php esc_html_e( 'Metodología:', 'econopapi-wp' ); ?></strong>
				<?php if ( $methodology_url ) : ?>
					<a href="<?php echo esc_url( $methodology_url ); ?>"><?php echo esc_html( $methodology_label ? $methodology_label : $methodology_url ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $methodology_label ); ?>
				<?php endif; ?>
			</span>
		<?php endif; ?>

		<?php if ( $analysis['repository'] || $analysis['commit'] || $analysis['path'] ) : ?>
			<span class="eco-plotly__provenance eco-plotly__analysis">
				<strong><?php esc_html_e( 'Análisis:', 'econopapi-wp' ); ?></strong>
				<?php if ( $analysis['repository'] ) : ?>
					<a href="<?php echo esc_url( $analysis['repository'] ); ?>"><?php esc_html_e( 'repositorio', 'econopapi-wp' ); ?></a>
				<?php endif; ?>
				<?php if ( $analysis['commit'] ) : ?>
					· <?php esc_html_e( 'commit', 'econopapi-wp' ); ?> <code><?php echo esc_html( sanitize_text_field( $analysis['commit'] ) ); ?></code>
				<?php endif; ?>
				<?php if ( $analysis['path'] ) : ?>
					· <code><?php echo esc_html( sanitize_text_field( $analysis['path'] ) ); ?></code>
				<?php endif; ?>
			</span>
		<?php endif; ?>

		<?php if ( $generated_at ) : ?>
			<span class="eco-plotly__provenance"><strong><?php esc_html_e( 'Corte:', 'econopapi-wp' ); ?></strong> <time datetime="<?php echo esc_attr( $generated_at ); ?>"><?php echo esc_html( $generated_at ); ?></time></span>
		<?php endif; ?>

		<?php if ( $note ) : ?>
			<span class="eco-plotly__provenance eco-plotly__note"><?php echo esc_html( $note ); ?></span>
		<?php endif; ?>
	</figcaption>

	<details class="eco-plotly__data"<?php echo $table_open ? ' open' : ''; ?>>
		<summary><?php esc_html_e( 'Tabla de datos (accesible)', 'econopapi-wp' ); ?></summary>
		<div class="eco-plotly__table-scroll" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Tabla de datos de la figura', 'econopapi-wp' ); ?>">
			<table>
				<caption><?php echo esc_html( $table_caption ); ?></caption>
				<thead>
					<tr>
						<?php foreach ( $manifest['table']['columns'] as $column ) : ?>
							<th scope="col">
								<?php echo esc_html( $column['label'] ); ?>
								<?php if ( ! empty( $column['unit'] ) ) : ?>
									<span class="eco-plotly__column-unit">(<?php echo esc_html( sanitize_text_field( $column['unit'] ) ); ?>)</span>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $manifest['table']['rows'] as $row ) : ?>
						<tr>
							<?php foreach ( $manifest['table']['columns'] as $column ) : ?>
								<td><?php echo esc_html( $format_cell( array_key_exists( $column['key'], $row ) ? $row[ $column['key'] ] : null ) ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php if ( $allow_download && $download_url ) : ?>
			<a class="eco-plotly__download" href="<?php echo esc_url( $download_url ); ?>" download><?php esc_html_e( 'Descargar manifiesto JSON', 'econopapi-wp' ); ?></a>
		<?php endif; ?>
	</details>
</figure>

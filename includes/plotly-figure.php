<?php
/**
 * Plotly Figure block support: assets, uploads and manifest validation.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ECONOPAPI_THEME_PLOTLY_MAX_BYTES' ) ) {
	define( 'ECONOPAPI_THEME_PLOTLY_MAX_BYTES', 5 * 1024 * 1024 );
}

/**
 * Registers the shared Plotly.js dependency.
 *
 * @return void
 */
function econopapi_theme_plotly_register_assets() {
	wp_register_script(
		'econopapi-theme-plotlyjs',
		get_stylesheet_directory_uri() . '/custom-blocks/plotly-figure/vendor/plotly-2.35.2.min.js',
		array(),
		'2.35.2',
		true
	);
}
add_action( 'init', 'econopapi_theme_plotly_register_assets', 9 );

/**
 * Allows administrators to upload JSON figure manifests.
 *
 * @param array $mimes Allowed MIME types.
 * @return array
 */
function econopapi_theme_plotly_upload_mimes( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['json'] = 'application/json';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'econopapi_theme_plotly_upload_mimes' );

/**
 * Keeps JSON uploads working when finfo identifies them as plain text.
 *
 * @param array       $data File data.
 * @param string      $file Full path to the file.
 * @param string      $filename Original filename.
 * @param array       $mimes Allowed MIME types.
 * @param string|false $real_mime Detected MIME type.
 * @return array
 */
function econopapi_theme_plotly_check_filetype( $data, $file, $filename, $mimes, $real_mime ) {
	unset( $file, $mimes );

	$extension = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
	$allowed   = array( 'application/json', 'application/x-json', 'text/json', 'text/x-json', 'text/plain' );

	if (
		'json' === $extension
		&& current_user_can( 'manage_options' )
		&& ( ! $real_mime || in_array( $real_mime, $allowed, true ) )
	) {
		$data['ext']  = 'json';
		$data['type'] = 'application/json';
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'econopapi_theme_plotly_check_filetype', 10, 5 );

/**
 * Validates an uploaded Econopapi figure before it enters the Media Library.
 * Other JSON documents remain unaffected.
 *
 * @param array $file Upload data.
 * @return array
 */
function econopapi_theme_plotly_prefilter_upload( $file ) {
	if ( empty( $file['name'] ) || 'json' !== strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) ) ) {
		return $file;
	}

	if ( empty( $file['tmp_name'] ) || ! is_readable( $file['tmp_name'] ) ) {
		return $file;
	}

	$size = filesize( $file['tmp_name'] );
	if ( false === $size || $size > ECONOPAPI_THEME_PLOTLY_MAX_BYTES ) {
		$file['error'] = __( 'El manifiesto JSON supera el límite de 5 MB.', 'econopapi-wp' );
		return $file;
	}

	$contents = file_get_contents( $file['tmp_name'] );
	$manifest = json_decode( (string) $contents, true );

	if ( ! is_array( $manifest ) || ! isset( $manifest['schemaVersion'], $manifest['figure'], $manifest['table'] ) ) {
		return $file;
	}

	$valid = econopapi_theme_plotly_validate_manifest( $manifest );
	if ( is_wp_error( $valid ) ) {
		$file['error'] = $valid->get_error_message();
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'econopapi_theme_plotly_prefilter_upload' );

/**
 * Validates the portable Econopapi Plotly manifest.
 *
 * @param mixed $manifest Decoded manifest.
 * @return true|WP_Error
 */
function econopapi_theme_plotly_validate_manifest( $manifest ) {
	if ( ! is_array( $manifest ) ) {
		return new WP_Error( 'eco_plotly_type', __( 'El manifiesto Plotly debe ser un objeto JSON.', 'econopapi-wp' ) );
	}

	if ( ! isset( $manifest['schemaVersion'] ) || 1 !== (int) $manifest['schemaVersion'] ) {
		return new WP_Error( 'eco_plotly_schema', __( 'schemaVersion debe ser exactamente 1.', 'econopapi-wp' ) );
	}

	if ( empty( $manifest['meta'] ) || ! is_array( $manifest['meta'] ) ) {
		return new WP_Error( 'eco_plotly_meta', __( 'Falta el objeto meta.', 'econopapi-wp' ) );
	}

	foreach ( array( 'title', 'alt' ) as $field ) {
		if ( empty( $manifest['meta'][ $field ] ) || ! is_string( $manifest['meta'][ $field ] ) ) {
			return new WP_Error( 'eco_plotly_meta_field', sprintf( __( 'meta.%s es obligatorio y debe ser texto.', 'econopapi-wp' ), $field ) );
		}
	}

	if ( empty( $manifest['meta']['sources'] ) || ! is_array( $manifest['meta']['sources'] ) ) {
		return new WP_Error( 'eco_plotly_sources', __( 'meta.sources debe contener al menos una fuente.', 'econopapi-wp' ) );
	}

	foreach ( $manifest['meta']['sources'] as $source ) {
		if ( ! is_array( $source ) || empty( $source['label'] ) || ! is_string( $source['label'] ) ) {
			return new WP_Error( 'eco_plotly_source_label', __( 'Cada fuente debe incluir un label de texto.', 'econopapi-wp' ) );
		}
	}

	if ( empty( $manifest['figure'] ) || ! is_array( $manifest['figure'] ) ) {
		return new WP_Error( 'eco_plotly_figure', __( 'Falta el objeto figure.', 'econopapi-wp' ) );
	}

	if ( ! isset( $manifest['figure']['data'] ) || ! is_array( $manifest['figure']['data'] ) ) {
		return new WP_Error( 'eco_plotly_data', __( 'figure.data es obligatorio y debe ser un arreglo.', 'econopapi-wp' ) );
	}

	if ( ! array_key_exists( 'layout', $manifest['figure'] ) || ! is_array( $manifest['figure']['layout'] ) ) {
		return new WP_Error( 'eco_plotly_layout', __( 'figure.layout es obligatorio y debe ser un objeto.', 'econopapi-wp' ) );
	}

	if ( isset( $manifest['figure']['frames'] ) && ! is_array( $manifest['figure']['frames'] ) ) {
		return new WP_Error( 'eco_plotly_frames', __( 'figure.frames debe ser un arreglo.', 'econopapi-wp' ) );
	}

	if ( isset( $manifest['config'] ) && ! is_array( $manifest['config'] ) ) {
		return new WP_Error( 'eco_plotly_config', __( 'config debe ser un objeto.', 'econopapi-wp' ) );
	}

	if ( empty( $manifest['table'] ) || ! is_array( $manifest['table'] ) ) {
		return new WP_Error( 'eco_plotly_table', __( 'Falta el objeto table.', 'econopapi-wp' ) );
	}

	if ( empty( $manifest['table']['columns'] ) || ! is_array( $manifest['table']['columns'] ) ) {
		return new WP_Error( 'eco_plotly_columns', __( 'table.columns debe contener al menos una columna.', 'econopapi-wp' ) );
	}

	if ( ! isset( $manifest['table']['rows'] ) || ! is_array( $manifest['table']['rows'] ) ) {
		return new WP_Error( 'eco_plotly_rows', __( 'table.rows es obligatorio y debe ser un arreglo.', 'econopapi-wp' ) );
	}

	$allowed_types = array( 'string', 'number', 'boolean', 'date', 'datetime' );
	foreach ( $manifest['table']['columns'] as $column ) {
		if ( ! is_array( $column ) || empty( $column['key'] ) || ! is_string( $column['key'] ) || empty( $column['label'] ) || ! is_string( $column['label'] ) ) {
			return new WP_Error( 'eco_plotly_column_fields', __( 'Cada columna necesita key y label de texto.', 'econopapi-wp' ) );
		}

		if ( isset( $column['type'] ) && ! in_array( $column['type'], $allowed_types, true ) ) {
			return new WP_Error( 'eco_plotly_column_type', __( 'El tipo de columna debe ser string, number, boolean, date o datetime.', 'econopapi-wp' ) );
		}
	}

	foreach ( $manifest['table']['rows'] as $row ) {
		if ( ! is_array( $row ) ) {
			return new WP_Error( 'eco_plotly_row', __( 'Cada fila de table.rows debe ser un objeto.', 'econopapi-wp' ) );
		}

		foreach ( $row as $value ) {
			if ( ! is_null( $value ) && ! is_scalar( $value ) ) {
				return new WP_Error( 'eco_plotly_cell', __( 'Las celdas de la tabla deben contener valores escalares o null.', 'econopapi-wp' ) );
			}
		}
	}

	return true;
}

/**
 * Reads and validates a manifest from a local Media Library attachment.
 *
 * @param int $source_id Attachment ID.
 * @return array|WP_Error
 */
function econopapi_theme_plotly_read_manifest( $source_id ) {
	$source_id = absint( $source_id );
	if ( ! $source_id || 'attachment' !== get_post_type( $source_id ) ) {
		return new WP_Error( 'eco_plotly_attachment', __( 'Selecciona un archivo JSON válido de la Biblioteca de medios.', 'econopapi-wp' ) );
	}

	$path = get_attached_file( $source_id );
	if ( ! $path || 'json' !== strtolower( pathinfo( $path, PATHINFO_EXTENSION ) ) || ! is_readable( $path ) ) {
		return new WP_Error( 'eco_plotly_file', __( 'El adjunto seleccionado no es un JSON legible.', 'econopapi-wp' ) );
	}

	$size = filesize( $path );
	if ( false === $size || $size > ECONOPAPI_THEME_PLOTLY_MAX_BYTES ) {
		return new WP_Error( 'eco_plotly_size', __( 'El manifiesto supera el límite de 5 MB.', 'econopapi-wp' ) );
	}

	$contents = file_get_contents( $path );
	if ( false === $contents ) {
		return new WP_Error( 'eco_plotly_read', __( 'No se pudo leer el manifiesto.', 'econopapi-wp' ) );
	}

	$manifest = json_decode( $contents, true );
	if ( JSON_ERROR_NONE !== json_last_error() ) {
		return new WP_Error( 'eco_plotly_json', sprintf( __( 'JSON inválido: %s', 'econopapi-wp' ), json_last_error_msg() ) );
	}

	$valid = econopapi_theme_plotly_validate_manifest( $manifest );
	if ( is_wp_error( $valid ) ) {
		return $valid;
	}

	return $manifest;
}

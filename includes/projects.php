<?php
/**
 * Projects custom post type and archive helpers.
 *
 * @package EconopapiWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns available project status options.
 *
 * @return array<string, string>
 */
function econopapi_get_project_status_options() {
	return array(
		'en-vivo'       => __( 'En vivo', 'econopapi-wp' ),
		'en-desarrollo' => __( 'En desarrollo', 'econopapi-wp' ),
		'activo'        => __( 'Activo', 'econopapi-wp' ),
		'pausado'       => __( 'Pausado', 'econopapi-wp' ),
	);
}

/**
 * Returns the label for a status slug.
 *
 * @param string $status_slug Status slug.
 * @return string
 */
function econopapi_get_project_status_label( $status_slug ) {
	$options = econopapi_get_project_status_options();
	$slug    = sanitize_key( (string) $status_slug );

	if ( isset( $options[ $slug ] ) ) {
		return $options[ $slug ];
	}

	return $options['en-vivo'];
}

/**
 * Registers project custom post type.
 *
 * @return void
 */
function econopapi_register_project_post_type() {
	$labels = array(
		'name'                  => __( 'Proyectos', 'econopapi-wp' ),
		'singular_name'         => __( 'Proyecto', 'econopapi-wp' ),
		'menu_name'             => __( 'Proyectos', 'econopapi-wp' ),
		'name_admin_bar'        => __( 'Proyecto', 'econopapi-wp' ),
		'add_new'               => __( 'Añadir nuevo', 'econopapi-wp' ),
		'add_new_item'          => __( 'Añadir nuevo proyecto', 'econopapi-wp' ),
		'new_item'              => __( 'Nuevo proyecto', 'econopapi-wp' ),
		'edit_item'             => __( 'Editar proyecto', 'econopapi-wp' ),
		'view_item'             => __( 'Ver proyecto', 'econopapi-wp' ),
		'all_items'             => __( 'Todos los proyectos', 'econopapi-wp' ),
		'search_items'          => __( 'Buscar proyectos', 'econopapi-wp' ),
		'not_found'             => __( 'No se encontraron proyectos.', 'econopapi-wp' ),
		'not_found_in_trash'    => __( 'No se encontraron proyectos en la papelera.', 'econopapi-wp' ),
		'featured_image'        => __( 'Imagen destacada del proyecto', 'econopapi-wp' ),
		'set_featured_image'    => __( 'Definir imagen destacada', 'econopapi-wp' ),
		'remove_featured_image' => __( 'Quitar imagen destacada', 'econopapi-wp' ),
		'use_featured_image'    => __( 'Usar como imagen destacada', 'econopapi-wp' ),
	);

	register_post_type(
		'project',
		array(
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => 'proyectos',
			'rewrite'             => array(
				'slug'       => 'proyecto',
				'with_front' => false,
			),
			'menu_icon'           => 'dashicons-portfolio',
			'show_in_rest'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'taxonomies'          => array( 'post_tag' ),
		)
	);
}
add_action( 'init', 'econopapi_register_project_post_type' );

/**
 * Registers project detail metabox.
 *
 * @return void
 */
function econopapi_register_project_meta_box() {
	add_meta_box(
		'econopapi_project_details',
		__( 'Detalles del proyecto', 'econopapi-wp' ),
		'econopapi_render_project_meta_box',
		'project',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'econopapi_register_project_meta_box' );

/**
 * Renders project details metabox.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function econopapi_render_project_meta_box( $post ) {
	$status_options = econopapi_get_project_status_options();
	$current_status = sanitize_key( (string) get_post_meta( $post->ID, 'econopapi_project_status', true ) );
	$project_url    = (string) get_post_meta( $post->ID, 'econopapi_project_url', true );
	$repo_url       = (string) get_post_meta( $post->ID, 'econopapi_project_repo_url', true );

	if ( '' === $current_status || ! isset( $status_options[ $current_status ] ) ) {
		$current_status = 'en-vivo';
	}

	wp_nonce_field( 'econopapi_project_meta_box', 'econopapi_project_meta_box_nonce' );
	?>
	<p>
		<label for="econopapi_project_status"><strong><?php esc_html_e( 'Estatus', 'econopapi-wp' ); ?></strong></label>
		<select id="econopapi_project_status" name="econopapi_project_status" style="width:100%; margin-top:6px;">
			<?php foreach ( $status_options as $status_slug => $status_label ) : ?>
				<option value="<?php echo esc_attr( $status_slug ); ?>" <?php selected( $current_status, $status_slug ); ?>>
					<?php echo esc_html( $status_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>

	<p>
		<label for="econopapi_project_url"><strong><?php esc_html_e( 'URL del proyecto', 'econopapi-wp' ); ?></strong></label>
		<input
			type="url"
			id="econopapi_project_url"
			name="econopapi_project_url"
			value="<?php echo esc_attr( $project_url ); ?>"
			placeholder="https://ejemplo.com"
			style="width:100%; margin-top:6px;"
		/>
		<span class="description"><?php esc_html_e( 'URL pública principal del proyecto (demo, app o landing).', 'econopapi-wp' ); ?></span>
	</p>

	<p>
		<label for="econopapi_project_repo_url"><strong><?php esc_html_e( 'URL del repositorio', 'econopapi-wp' ); ?></strong></label>
		<input
			type="url"
			id="econopapi_project_repo_url"
			name="econopapi_project_repo_url"
			value="<?php echo esc_attr( $repo_url ); ?>"
			placeholder="https://github.com/usuario/repositorio"
			style="width:100%; margin-top:6px;"
		/>
		<span class="description"><?php esc_html_e( 'Opcional. Enlace al repositorio de código fuente del proyecto.', 'econopapi-wp' ); ?></span>
	</p>
	<?php
}

/**
 * Persists project metabox fields.
 *
 * @param int $post_id Project post ID.
 * @return void
 */
function econopapi_save_project_meta_box( $post_id ) {
	if ( ! isset( $_POST['econopapi_project_meta_box_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['econopapi_project_meta_box_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'econopapi_project_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$status_options = econopapi_get_project_status_options();
	$status_input   = isset( $_POST['econopapi_project_status'] ) ? sanitize_key( (string) wp_unslash( $_POST['econopapi_project_status'] ) ) : '';
	$project_url    = isset( $_POST['econopapi_project_url'] ) ? esc_url_raw( (string) wp_unslash( $_POST['econopapi_project_url'] ) ) : '';
	$repo_url       = isset( $_POST['econopapi_project_repo_url'] ) ? esc_url_raw( (string) wp_unslash( $_POST['econopapi_project_repo_url'] ) ) : '';

	if ( isset( $status_options[ $status_input ] ) ) {
		update_post_meta( $post_id, 'econopapi_project_status', $status_input );
	}

	if ( '' !== $project_url ) {
		update_post_meta( $post_id, 'econopapi_project_url', $project_url );
	} else {
		delete_post_meta( $post_id, 'econopapi_project_url' );
	}

	if ( '' !== $repo_url ) {
		update_post_meta( $post_id, 'econopapi_project_repo_url', $repo_url );
	} else {
		delete_post_meta( $post_id, 'econopapi_project_repo_url' );
	}
}
add_action( 'save_post_project', 'econopapi_save_project_meta_box' );

/**
 * Returns normalized project meta for a post ID.
 *
 * @param int $post_id Project post ID.
 * @return array<string, string>
 */
function econopapi_get_project_meta( $post_id ) {
	$status = sanitize_key( (string) get_post_meta( $post_id, 'econopapi_project_status', true ) );

	if ( '' === $status ) {
		$status = 'en-vivo';
	}

	return array(
		'status'       => $status,
		'status_label' => econopapi_get_project_status_label( $status ),
		'project_url'  => econopapi_get_post_meta_url( $post_id, 'econopapi_project_url' ),
		'repo_url'     => econopapi_get_post_meta_url( $post_id, 'econopapi_project_repo_url' ),
	);
}

/**
 * Returns a human friendly label for a project URL.
 *
 * @param string $url URL to format.
 * @return string
 */
function econopapi_get_project_url_label( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return '';
	}

	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( is_string( $host ) && '' !== $host ) {
		return preg_replace( '/^www\./', '', $host );
	}

	return $url;
}

/**
 * Builds a related posts query using shared tags when available.
 *
 * @param int    $post_id          Current project ID.
 * @param string $related_post_type Post type to query.
 * @param int    $posts_per_page   Posts to fetch.
 * @return WP_Query
 */
function econopapi_get_related_content_query( $post_id, $related_post_type, $posts_per_page = 2 ) {
	$tag_ids = wp_get_post_terms(
		$post_id,
		'post_tag',
		array(
			'fields' => 'ids',
		)
	);

	$args = array(
		'post_type'           => $related_post_type,
		'posts_per_page'      => $posts_per_page,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	if ( ! empty( $tag_ids ) ) {
		$args['tag__in'] = array_map( 'intval', $tag_ids );
		$args['orderby'] = 'date';
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() || empty( $tag_ids ) ) {
		return $query;
	}

	return new WP_Query(
		array(
			'post_type'           => $related_post_type,
			'posts_per_page'      => $posts_per_page,
			'post__not_in'        => array( $post_id ),
			'ignore_sticky_posts' => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
		)
	);
}

/**
 * Returns a list of related blog posts for a project.
 *
 * @param int $post_id Current project ID.
 * @param int $posts_per_page Posts to fetch.
 * @return WP_Query
 */
function econopapi_get_related_blog_posts( $post_id, $posts_per_page = 2 ) {
	return econopapi_get_related_content_query( $post_id, 'post', $posts_per_page );
}

/**
 * Returns a list of related projects.
 *
 * @param int $post_id Current project ID.
 * @param int $posts_per_page Posts to fetch.
 * @return WP_Query
 */
function econopapi_get_related_projects( $post_id, $posts_per_page = 2 ) {
	return econopapi_get_related_content_query( $post_id, 'project', $posts_per_page );
}

/**
 * Renders a project card for archive loops.
 *
 * @return string
 */
function econopapi_render_project_archive_card() {
	$project_id        = (int) get_the_ID();
	$project_permalink = get_permalink( $project_id );
	$project_meta      = econopapi_get_project_meta( $project_id );
	$project_status    = $project_meta['status'];
	$project_url       = '' !== $project_meta['project_url'] ? $project_meta['project_url'] : get_permalink();
	$excerpt          = trim( (string) get_the_excerpt() );
	$description      = trim( (string) wp_strip_all_tags( get_the_content() ) );
	$tags             = get_the_terms( $project_id, 'post_tag' );

	if ( ! is_array( $tags ) ) {
		$tags = array();
	}

	if ( '' === $description ) {
		$description = $excerpt;
	}

	$is_external = false !== wp_parse_url( $project_url, PHP_URL_HOST );

	ob_start();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'eco-project-card' ); ?> role="listitem">
		<div class="eco-project-card__inner">
			<div class="eco-project-card__head">
				<h2 class="eco-project-card__title">
					<a href="<?php echo esc_url( $project_permalink ); ?>">
						<?php the_title(); ?>
					</a>
				</h2>
				<span class="eco-project-card__status eco-project-card__status--<?php echo esc_attr( $project_status ); ?>">
					<?php echo esc_html( econopapi_get_project_status_label( $project_status ) ); ?>
				</span>
			</div>

			<?php if ( '' !== $excerpt ) : ?>
				<p class="eco-project-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
			<?php endif; ?>

			<?php if ( '' !== $description ) : ?>
				<p class="eco-project-card__description"><?php echo esc_html( wp_trim_words( $description, 28 ) ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $tags ) ) : ?>
				<ul class="eco-project-card__tags" aria-label="<?php esc_attr_e( 'Tecnologías del proyecto', 'econopapi-wp' ); ?>">
					<?php foreach ( $tags as $tag ) : ?>
						<li><?php echo esc_html( $tag->name ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<p class="eco-project-card__links">
				<a href="<?php echo esc_url( $project_url ); ?>"<?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<?php echo esc_html( econopapi_get_project_url_label( $project_url ) ); ?>
				</a>
			</p>
		</div>
	</article>
	<?php

	return (string) ob_get_clean();
}

/**
 * Enqueues project archive stylesheet.
 *
 * @return void
 */
function econopapi_enqueue_project_archive_assets() {
	if ( ! is_post_type_archive( 'project' ) ) {
		return;
	}

	wp_enqueue_style(
		'econopapi-projects-archive-style',
		get_stylesheet_directory_uri() . '/assets/css/projects-archive.css',
		array( 'econopapi-theme-style' ),
		ECONOPAPI_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'econopapi_enqueue_project_archive_assets', 15 );

/**
 * Configures project archive pagination.
 *
 * @param WP_Query $query Query instance.
 * @return void
 */
function econopapi_configure_project_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'project' ) ) {
		return;
	}

	$query->set( 'posts_per_page', 12 );
	$query->set( 'orderby', 'date' );
	$query->set( 'order', 'DESC' );
}
add_action( 'pre_get_posts', 'econopapi_configure_project_archive_query' );

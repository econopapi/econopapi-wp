( function ( blocks, blockEditor, components, element, i18n, serverSideRender, apiFetch ) {
	'use strict';

	var el = element.createElement;
	var Fragment = element.Fragment;
	var InspectorControls = blockEditor.InspectorControls;
	var MediaUpload = blockEditor.MediaUpload;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var RangeControl = components.RangeControl;
	var ToggleControl = components.ToggleControl;
	var Button = components.Button;
	var ServerSideRender = serverSideRender;
	var __ = i18n.__;

	blocks.registerBlockType( 'econopapi/plotly-figure', {
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'eco-plotly-editor' } );
			var manifestState = element.useState( null );
			var manifest = manifestState[0];
			var setManifest = manifestState[1];
			var manifestErrorState = element.useState( '' );
			var manifestError = manifestErrorState[0];
			var setManifestError = manifestErrorState[1];
			var sourceId = attributes.sourceId;

			element.useEffect( function () {
				var cancelled = false;
				setManifest( null );
				setManifestError( '' );

				if ( ! sourceId ) {
					return undefined;
				}

				apiFetch( { path: '/wp/v2/media/' + sourceId } )
					.then( function ( media ) {
						var url = media.source_url || ( media.guid && media.guid.rendered );
						if ( ! url ) {
							throw new Error( 'Missing manifest URL' );
						}
						return window.fetch( url, { credentials: 'same-origin' } );
					} )
					.then( function ( response ) {
						if ( ! response.ok ) {
							throw new Error( 'Manifest request failed' );
						}
						return response.json();
					} )
					.then( function ( data ) {
						if ( cancelled ) {
							return;
						}
						if ( ! data || ! data.meta ) {
							throw new Error( 'Invalid manifest metadata' );
						}
						setManifest( data );
					} )
					.catch( function () {
						if ( ! cancelled ) {
							setManifestError( __( 'No se pudieron leer los metadatos del JSON.', 'econopapi-wp' ) );
						}
					} );

				return function () {
					cancelled = true;
				};
			}, [ sourceId ] );

			function manifestValue( key ) {
				if ( ! manifest || ! manifest.meta ) {
					return '';
				}

				var meta = manifest.meta;
				if ( key === 'sourceLabel' ) {
					return meta.sources && meta.sources[0] ? meta.sources[0].label || '' : '';
				}
				if ( key === 'sourceUrl' ) {
					return meta.sources && meta.sources[0] ? meta.sources[0].url || '' : '';
				}
				if ( key === 'methodologyLabel' ) {
					return meta.methodology ? meta.methodology.label || '' : '';
				}
				if ( key === 'methodologyUrl' ) {
					return meta.methodology ? meta.methodology.url || '' : '';
				}
				return typeof meta[ key ] === 'string' ? meta[ key ] : '';
			}

			function fieldValue( key ) {
				return attributes[ key ] || manifestValue( key );
			}

			function selectManifest( media ) {
				if ( ! media || ! media.id ) {
					return;
				}
				setAttributes( {
					sourceId: media.id,
					sourceName: media.filename || media.title || __( 'Manifiesto JSON', 'econopapi-wp' ),
					kicker: '',
					title: '',
					description: '',
					alt: '',
					unit: '',
					sourceLabel: '',
					sourceUrl: '',
					methodologyLabel: '',
					methodologyUrl: '',
					note: '',
				} );
			}

			function textControl( key, label, options ) {
				options = options || {};
				return el( TextControl, {
					label: label,
					type: options.type || 'text',
					value: fieldValue( key ),
					help: options.help,
					onChange: function ( value ) {
						var update = {};
						update[ key ] = value;
						setAttributes( update );
					},
				} );
			}

			function textareaControl( key, label, help ) {
				return el( TextareaControl, {
					label: label,
					value: fieldValue( key ),
					help: help,
					onChange: function ( value ) {
						var update = {};
						update[ key ] = value;
						setAttributes( update );
					},
				} );
			}

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Fuente de la figura', 'econopapi-wp' ), initialOpen: true },
						el( MediaUpload, {
							allowedTypes: [ 'application/json' ],
							value: attributes.sourceId,
							onSelect: selectManifest,
							render: function ( upload ) {
								return el(
									Button,
									{ variant: 'primary', onClick: upload.open },
									attributes.sourceId
										? __( 'Reemplazar manifiesto JSON', 'econopapi-wp' )
										: __( 'Seleccionar manifiesto JSON', 'econopapi-wp' )
								);
							},
						} ),
						attributes.sourceId
							? el(
								Fragment,
								null,
								el( 'p', { className: 'eco-plotly-editor__filename' }, attributes.sourceName || '#' + attributes.sourceId ),
								manifestError
									? el( 'p', { className: 'eco-plotly-editor__help', role: 'alert' }, manifestError )
									: null,
								! manifest && ! manifestError
									? el( 'p', { className: 'eco-plotly-editor__help' }, __( 'Cargando metadatos del manifiesto…', 'econopapi-wp' ) )
									: null,
								el(
									Button,
									{
										variant: 'link',
										isDestructive: true,
										onClick: function () {
											setAttributes( { sourceId: 0, sourceName: '' } );
										},
									},
									__( 'Quitar archivo', 'econopapi-wp' )
								)
							)
							: el(
								'p',
								{ className: 'eco-plotly-editor__help' },
								__( 'Sube el JSON generado desde Python. Debe incluir figura, tabla y procedencia.', 'econopapi-wp' )
							)
					),
					el(
						PanelBody,
						{ title: __( 'Contenido editorial', 'econopapi-wp' ), initialOpen: false },
						el( 'p', { className: 'eco-plotly-editor__help' }, __( 'Los valores provienen del manifiesto. Si los editas, el cambio se guarda como ajuste de este bloque.', 'econopapi-wp' ) ),
						textControl( 'kicker', __( 'Kicker', 'econopapi-wp' ) ),
						textControl( 'title', __( 'Título', 'econopapi-wp' ) ),
						textareaControl( 'description', __( 'Descripción', 'econopapi-wp' ) ),
						textareaControl( 'alt', __( 'Texto alternativo', 'econopapi-wp' ), __( 'Describe qué muestra la figura y su lectura principal.', 'econopapi-wp' ) ),
						textControl( 'unit', __( 'Unidad', 'econopapi-wp' ) )
					),
					el(
						PanelBody,
						{ title: __( 'Trazabilidad', 'econopapi-wp' ), initialOpen: false },
						textControl( 'sourceLabel', __( 'Etiqueta de la fuente principal', 'econopapi-wp' ) ),
						textControl( 'sourceUrl', __( 'URL de la fuente principal', 'econopapi-wp' ), { type: 'url' } ),
						textControl( 'methodologyLabel', __( 'Etiqueta de metodología', 'econopapi-wp' ) ),
						textControl( 'methodologyUrl', __( 'URL de metodología', 'econopapi-wp' ), { type: 'url' } ),
						textareaControl( 'note', __( 'Nota metodológica', 'econopapi-wp' ) )
					),
					el(
						PanelBody,
						{ title: __( 'Diseño y datos', 'econopapi-wp' ), initialOpen: false },
						el( RangeControl, {
							label: __( 'Altura del gráfico (px)', 'econopapi-wp' ),
							value: attributes.height || 480,
							min: 240,
							max: 1000,
							step: 20,
							onChange: function ( value ) {
								setAttributes( { height: value } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Mostrar la tabla abierta inicialmente', 'econopapi-wp' ),
							checked: !! attributes.tableOpen,
							onChange: function ( value ) {
								setAttributes( { tableOpen: value } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Permitir descargar el manifiesto JSON', 'econopapi-wp' ),
							checked: attributes.allowDownload !== false,
							onChange: function ( value ) {
								setAttributes( { allowDownload: value } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el( ServerSideRender, {
						block: 'econopapi/plotly-figure',
						attributes: attributes,
					} )
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n, window.wp.serverSideRender, window.wp.apiFetch );

( function ( blocks, blockEditor, components, element, i18n ) {
	const el = element.createElement;
	const __ = i18n.__;
	const useBlockProps = blockEditor.useBlockProps;
	const RichText = blockEditor.RichText;
	const InspectorControls = blockEditor.InspectorControls;
	const PanelBody = components.PanelBody;
	const TextControl = components.TextControl;

	blocks.registerBlockType( 'econopapi/hero', {
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;
			const blockProps = useBlockProps( { className: 'eco-hero-block' } );

			return el(
				element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Botones', 'econopapi-wp' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Texto botón primario', 'econopapi-wp' ),
							value: attributes.primaryButtonLabel,
							onChange: function ( value ) {
								setAttributes( { primaryButtonLabel: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'URL botón primario', 'econopapi-wp' ),
							value: attributes.primaryButtonUrl,
							onChange: function ( value ) {
								setAttributes( { primaryButtonUrl: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Texto botón secundario', 'econopapi-wp' ),
							value: attributes.secondaryButtonLabel,
							onChange: function ( value ) {
								setAttributes( { secondaryButtonLabel: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'URL botón secundario', 'econopapi-wp' ),
							value: attributes.secondaryButtonUrl,
							onChange: function ( value ) {
								setAttributes( { secondaryButtonUrl: value } );
							},
						} )
					)
				),
				el(
					'section',
					blockProps,
					el(
						'div',
						{ className: 'eco-hero' },
						el(
							'div',
							{ className: 'eco-container eco-hero-content' },
							el( RichText, {
								tagName: 'p',
								className: 'eco-hero-tagline',
								placeholder: __( 'Tagline', 'econopapi-wp' ),
								value: attributes.tagline,
								onChange: function ( value ) {
									setAttributes( { tagline: value } );
								},
							} ),
							el( RichText, {
								tagName: 'h1',
								className: 'eco-hero-title',
								placeholder: __( 'Título principal', 'econopapi-wp' ),
								value: attributes.title,
								onChange: function ( value ) {
									setAttributes( { title: value } );
								},
							} ),
							el( RichText, {
								tagName: 'p',
								className: 'eco-hero-description',
								placeholder: __( 'Descripción', 'econopapi-wp' ),
								value: attributes.description,
								onChange: function ( value ) {
									setAttributes( { description: value } );
								},
							} ),
							el(
								'div',
								{ className: 'eco-hero-actions' },
								el( 'span', { className: 'eco-btn eco-btn-primary' }, attributes.primaryButtonLabel || __( 'Botón primario', 'econopapi-wp' ) ),
								el( 'span', { className: 'eco-btn eco-btn-secondary' }, attributes.secondaryButtonLabel || __( 'Botón secundario', 'econopapi-wp' ) )
							)
						)
					)
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n );

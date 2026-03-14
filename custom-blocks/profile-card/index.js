( function ( blocks, blockEditor, components, element, i18n ) {
	const el = element.createElement;
	const __ = i18n.__;
	const useBlockProps = blockEditor.useBlockProps;
	const RichText = blockEditor.RichText;
	const InspectorControls = blockEditor.InspectorControls;
	const PanelBody = components.PanelBody;
	const SelectControl = components.SelectControl;
	const TextControl = components.TextControl;
	const ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'econopapi/profile-card', {
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;
			const blockProps = useBlockProps( { className: 'eco-profile-card-block' } );
			const selectedVariant = [ 'minimal', 'gradient', 'neon-soft' ].indexOf( attributes.cardVariant ) !== -1
				? attributes.cardVariant
				: 'gradient';
			const cardClassName =
				'eco-profile-card is-variant-' +
				selectedVariant +
				( attributes.showGlassEffect ? ' is-glass' : '' );

			const initialsFallback = ( attributes.avatarInitials || __( 'DL', 'econopapi-wp' ) ).slice( 0, 3 ).toUpperCase();

			return el(
				element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Variantes visuales', 'econopapi-wp' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Estilo de tarjeta', 'econopapi-wp' ),
							value: selectedVariant,
							options: [
								{ label: __( 'Minimal', 'econopapi-wp' ), value: 'minimal' },
								{ label: __( 'Gradient', 'econopapi-wp' ), value: 'gradient' },
								{ label: __( 'Neon Soft', 'econopapi-wp' ), value: 'neon-soft' },
							],
							onChange: function ( value ) {
								setAttributes( { cardVariant: value } );
							},
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Identidad', 'econopapi-wp' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Nombre', 'econopapi-wp' ),
							value: attributes.fullName,
							onChange: function ( value ) {
								setAttributes( { fullName: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Usuario', 'econopapi-wp' ),
							value: attributes.username,
							onChange: function ( value ) {
								setAttributes( { username: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Rol o tagline', 'econopapi-wp' ),
							value: attributes.role,
							onChange: function ( value ) {
								setAttributes( { role: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Iniciales (fallback avatar)', 'econopapi-wp' ),
							value: attributes.avatarInitials,
							onChange: function ( value ) {
								setAttributes( { avatarInitials: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Avatar URL', 'econopapi-wp' ),
							value: attributes.avatarUrl,
							onChange: function ( value ) {
								setAttributes( { avatarUrl: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Avatar ALT', 'econopapi-wp' ),
							value: attributes.avatarAlt,
							onChange: function ( value ) {
								setAttributes( { avatarAlt: value } );
							},
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Contacto y enlaces', 'econopapi-wp' ), initialOpen: false },
						el( ToggleControl, {
							label: __( 'Mostrar email', 'econopapi-wp' ),
							checked: !! attributes.showEmail,
							onChange: function ( value ) {
								setAttributes( { showEmail: !! value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Email', 'econopapi-wp' ),
							value: attributes.email,
							onChange: function ( value ) {
								setAttributes( { email: value } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Mostrar GitHub', 'econopapi-wp' ),
							checked: !! attributes.showGithub,
							onChange: function ( value ) {
								setAttributes( { showGithub: !! value } );
							},
						} ),
						el( TextControl, {
							label: __( 'GitHub URL', 'econopapi-wp' ),
							value: attributes.githubUrl,
							onChange: function ( value ) {
								setAttributes( { githubUrl: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'GitHub label', 'econopapi-wp' ),
							value: attributes.githubLabel,
							onChange: function ( value ) {
								setAttributes( { githubLabel: value } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Mostrar LinkedIn', 'econopapi-wp' ),
							checked: !! attributes.showLinkedin,
							onChange: function ( value ) {
								setAttributes( { showLinkedin: !! value } );
							},
						} ),
						el( TextControl, {
							label: __( 'LinkedIn URL', 'econopapi-wp' ),
							value: attributes.linkedinUrl,
							onChange: function ( value ) {
								setAttributes( { linkedinUrl: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'LinkedIn label', 'econopapi-wp' ),
							value: attributes.linkedinLabel,
							onChange: function ( value ) {
								setAttributes( { linkedinLabel: value } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Mostrar YouTube', 'econopapi-wp' ),
							checked: !! attributes.showYoutube,
							onChange: function ( value ) {
								setAttributes( { showYoutube: !! value } );
							},
						} ),
						el( TextControl, {
							label: __( 'YouTube URL', 'econopapi-wp' ),
							value: attributes.youtubeUrl,
							onChange: function ( value ) {
								setAttributes( { youtubeUrl: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'YouTube label', 'econopapi-wp' ),
							value: attributes.youtubeLabel,
							onChange: function ( value ) {
								setAttributes( { youtubeLabel: value } );
							},
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Ubicación y diseño', 'econopapi-wp' ), initialOpen: false },
						el( TextControl, {
							label: __( 'Texto de sección ubicación', 'econopapi-wp' ),
							value: attributes.locationLabel,
							onChange: function ( value ) {
								setAttributes( { locationLabel: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Ubicación', 'econopapi-wp' ),
							value: attributes.location,
							onChange: function ( value ) {
								setAttributes( { location: value } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Usar acabado glass premium', 'econopapi-wp' ),
							checked: !! attributes.showGlassEffect,
							onChange: function ( value ) {
								setAttributes( { showGlassEffect: !! value } );
							},
						} )
					)
				),
				el(
					'section',
					blockProps,
					el(
						'article',
						{
							className: cardClassName,
						},
						el(
							'div',
							{ className: 'eco-profile-card__avatar-wrap' },
							attributes.avatarUrl
								? el( 'img', {
									src: attributes.avatarUrl,
									alt: attributes.avatarAlt || attributes.fullName || __( 'Avatar', 'econopapi-wp' ),
									className: 'eco-profile-card__avatar-image',
								} )
								: el( 'span', { className: 'eco-profile-card__avatar-fallback', 'aria-hidden': true }, initialsFallback )
						),
						el( RichText, {
							tagName: 'h3',
							className: 'eco-profile-card__name',
							placeholder: __( 'Nombre completo', 'econopapi-wp' ),
							value: attributes.fullName,
							onChange: function ( value ) {
								setAttributes( { fullName: value } );
							},
						} ),
						el( RichText, {
							tagName: 'p',
							className: 'eco-profile-card__username',
							placeholder: __( '@usuario', 'econopapi-wp' ),
							value: attributes.username,
							onChange: function ( value ) {
								setAttributes( { username: value } );
							},
						} ),
						el( RichText, {
							tagName: 'p',
							className: 'eco-profile-card__role',
							placeholder: __( 'Rol o especialidad', 'econopapi-wp' ),
							value: attributes.role,
							onChange: function ( value ) {
								setAttributes( { role: value } );
							},
						} ),
						el(
							'div',
							{ className: 'eco-profile-card__contact' },
							attributes.showEmail && attributes.email ? el( 'p', { className: 'eco-profile-card__line' }, attributes.email ) : null,
							attributes.showGithub && attributes.githubUrl ? el( 'p', { className: 'eco-profile-card__line eco-profile-card__line--link' }, attributes.githubLabel || __( 'GitHub', 'econopapi-wp' ) ) : null,
							attributes.showLinkedin && attributes.linkedinUrl ? el( 'p', { className: 'eco-profile-card__line eco-profile-card__line--link' }, attributes.linkedinLabel || __( 'LinkedIn', 'econopapi-wp' ) ) : null,
							attributes.showYoutube && attributes.youtubeUrl ? el( 'p', { className: 'eco-profile-card__line eco-profile-card__line--link' }, attributes.youtubeLabel || __( 'YouTube', 'econopapi-wp' ) ) : null
						),
						el(
							'div',
							{ className: 'eco-profile-card__location' },
							el( 'p', { className: 'eco-profile-card__location-label' }, attributes.locationLabel || __( 'Basado en', 'econopapi-wp' ) ),
							el( 'p', { className: 'eco-profile-card__location-value' }, attributes.location || __( 'México', 'econopapi-wp' ) )
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

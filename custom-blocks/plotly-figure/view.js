( function () {
	'use strict';

	function clone( value ) {
		return JSON.parse( JSON.stringify( value ) );
	}

	function merge( target, source ) {
		var output = target && typeof target === 'object' ? target : {};
		if ( ! source || typeof source !== 'object' ) {
			return output;
		}

		Object.keys( source ).forEach( function ( key ) {
			var value = source[ key ];
			if ( value && typeof value === 'object' && ! Array.isArray( value ) ) {
				output[ key ] = merge( output[ key ], value );
			} else {
				output[ key ] = value;
			}
		} );
		return output;
	}

	function currentTheme() {
		var bodyTheme = document.body ? document.body.getAttribute( 'data-theme' ) : '';
		if ( bodyTheme === 'dark' || bodyTheme === 'light' ) {
			return bodyTheme;
		}

		var rootTheme = document.documentElement.getAttribute( 'data-theme' );
		if ( rootTheme === 'dark' || rootTheme === 'light' ) {
			return rootTheme;
		}

		return window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light';
	}

	function tokens( figure ) {
		var styles = window.getComputedStyle( figure );
		function read( name ) {
			return styles.getPropertyValue( name ).trim();
		}

		return {
			surface: read( '--eco-plotly-surface' ),
			text: read( '--eco-plotly-text' ),
			muted: read( '--eco-plotly-muted' ),
			border: read( '--eco-plotly-border' ),
			grid: read( '--eco-plotly-grid' ),
			fontFamily: read( '--eco-plotly-font-family' ) || 'inherit',
			colorway: [
				read( '--eco-plotly-series-1' ),
				read( '--eco-plotly-series-2' ),
				read( '--eco-plotly-series-3' ),
				read( '--eco-plotly-series-4' ),
				read( '--eco-plotly-series-5' ),
				read( '--eco-plotly-series-6' ),
			].filter( Boolean ),
		};
	}

	function themeFont( font, color, family ) {
		var output = merge( {}, font || {} );
		output.color = color;
		if ( family ) {
			output.family = family;
		}
		return output;
	}

	function themedAxis( axis, colors ) {
		var output = merge( {}, axis || {} );
		output.gridcolor = colors.grid;
		output.zerolinecolor = colors.grid;
		output.linecolor = colors.border;
		output.automargin = true;
		output.tickfont = themeFont( output.tickfont, colors.muted, colors.fontFamily );

		if ( output.title ) {
			if ( typeof output.title === 'string' ) {
				output.title = { text: output.title };
			} else {
				output.title = merge( {}, output.title );
			}
			output.title.font = themeFont( output.title.font, colors.muted, colors.fontFamily );
		}
		return output;
	}

	function needsCartesianAxes( data ) {
		var nonCartesian = [ 'pie', 'sunburst', 'treemap', 'sankey', 'funnel', 'parcoords', 'parcats', 'icicle' ];
		return data.some( function ( trace ) {
			return nonCartesian.indexOf( trace.type || 'scatter' ) === -1;
		} );
	}

	function themedLayout( baseLayout, colors, data ) {
		var layout = clone( baseLayout || {} );
		delete layout.template;

		layout.paper_bgcolor = 'rgba(0,0,0,0)';
		layout.plot_bgcolor = 'rgba(0,0,0,0)';
		layout.font = themeFont( layout.font, colors.text, colors.fontFamily );
		layout.colorway = colors.colorway;
		layout.hoverlabel = merge( {}, layout.hoverlabel || {} );
		layout.hoverlabel.bgcolor = colors.surface;
		layout.hoverlabel.bordercolor = colors.border;
		layout.hoverlabel.font = themeFont( layout.hoverlabel.font, colors.text, colors.fontFamily );
		layout.legend = merge( {}, layout.legend || {} );
		layout.legend.bgcolor = 'rgba(0,0,0,0)';
		layout.legend.font = themeFont( layout.legend.font, colors.muted, colors.fontFamily );
		layout.margin = merge( {}, layout.margin || {} );

		if (
			layout.showlegend !== false &&
			layout.legend.orientation === 'h' &&
			typeof layout.legend.y === 'undefined'
		) {
			var hasInternalTitle = !! layout.title;
			layout.legend.x = typeof layout.legend.x === 'undefined' ? 0 : layout.legend.x;
			layout.legend.xanchor = layout.legend.xanchor || 'left';
			layout.legend.y = hasInternalTitle ? 1.18 : 1.08;
			layout.legend.yanchor = 'bottom';
			layout.margin.t = Math.max( Number( layout.margin.t ) || 0, hasInternalTitle ? 120 : 80 );
		}

		if ( needsCartesianAxes( data ) ) {
			layout.xaxis = layout.xaxis || {};
			layout.yaxis = layout.yaxis || {};
		}

		Object.keys( layout ).forEach( function ( key ) {
			if ( /^[xy]axis\d*$/.test( key ) ) {
				layout[ key ] = themedAxis( layout[ key ], colors );
			}
		} );

		if ( layout.title ) {
			if ( typeof layout.title === 'string' ) {
				layout.title = { text: layout.title };
			}
			layout.title.font = themeFont( layout.title.font, colors.text, colors.fontFamily );
		}

		if ( Array.isArray( layout.annotations ) ) {
			layout.annotations = layout.annotations.map( function ( annotation ) {
				var output = merge( {}, annotation );
				output.font = themeFont( output.font, colors.muted, colors.fontFamily );
				return output;
			} );
		}

		if ( Array.isArray( layout.updatemenus ) ) {
			layout.updatemenus = layout.updatemenus.map( function ( menu ) {
				var output = merge( {}, menu );
				output.bgcolor = colors.surface;
				output.bordercolor = colors.border;
				output.font = themeFont( output.font, colors.text, colors.fontFamily );
				return output;
			} );
		}

		return layout;
	}

	function showError( figure, message ) {
		var error = figure.querySelector( '[data-eco-plotly-error]' );
		figure.classList.add( 'eco-plotly--error' );
		if ( error ) {
			error.textContent = message;
			error.hidden = false;
		}
	}

	function clearError( figure ) {
		var error = figure.querySelector( '[data-eco-plotly-error]' );
		figure.classList.remove( 'eco-plotly--error' );
		if ( error ) {
			error.textContent = '';
			error.hidden = true;
		}
	}

	function render( figure ) {
		var payloadElement = figure.querySelector( '.eco-plotly__payload' );
		var chart = figure.querySelector( '.eco-plotly__chart' );
		if ( ! payloadElement || ! chart ) {
			return;
		}

		var payload;
		try {
			payload = JSON.parse( payloadElement.textContent );
		} catch ( error ) {
			showError( figure, 'El manifiesto de la figura no contiene JSON válido.' );
			return;
		}

		if ( ! window.Plotly || ! payload.figure || ! Array.isArray( payload.figure.data ) ) {
			showError( figure, 'No fue posible iniciar Plotly. La tabla de datos sigue disponible.' );
			return;
		}

		var theme = currentTheme();
		var figureData = clone( payload.figure );
		var layout = themedLayout( figureData.layout, tokens( figure ), figureData.data );
		var config = payload.config && typeof payload.config === 'object' ? clone( payload.config ) : {};
		config.responsive = true;
		config.displaylogo = false;
		if ( typeof config.displayModeBar === 'undefined' ) {
			config.displayModeBar = false;
		}

		figure.dataset.ecoPlotlyInitialized = 'true';
		figure.dataset.ecoPlotlyPayload = payloadElement.textContent;
		figure.dataset.ecoPlotlyTheme = theme;
		chart.setAttribute( 'aria-busy', 'true' );
		clearError( figure );

		try {
			Promise.resolve( window.Plotly.react( chart, figureData.data, layout, config ) )
				.then( function () {
					chart.removeAttribute( 'aria-busy' );
				} )
				.catch( function ( error ) {
					chart.removeAttribute( 'aria-busy' );
					showError( figure, 'No fue posible renderizar la figura. Revisa el manifiesto; la tabla sigue disponible.' );
					if ( window.console && window.console.error ) {
						window.console.error( 'Econopapi Plotly Figure:', error );
					}
				} );
		} catch ( error ) {
			chart.removeAttribute( 'aria-busy' );
			showError( figure, 'No fue posible renderizar la figura. Revisa el manifiesto; la tabla sigue disponible.' );
			if ( window.console && window.console.error ) {
				window.console.error( 'Econopapi Plotly Figure:', error );
			}
		}
	}

	function scan() {
		document.querySelectorAll( '[data-eco-plotly]' ).forEach( function ( figure ) {
			var payload = figure.querySelector( '.eco-plotly__payload' );
			if ( ! payload ) {
				return;
			}

			if (
				figure.dataset.ecoPlotlyInitialized !== 'true' ||
				figure.dataset.ecoPlotlyPayload !== payload.textContent
			) {
				render( figure );
			}
		} );
	}

	function renderTheme() {
		var theme = currentTheme();
		document.querySelectorAll( '[data-eco-plotly]' ).forEach( function ( figure ) {
			if ( figure.dataset.ecoPlotlyTheme !== theme ) {
				render( figure );
			}
		} );
	}

	function start() {
		scan();

		var observer = new MutationObserver( function ( mutations ) {
			var shouldScan = false;
			var shouldRenderTheme = false;

			mutations.forEach( function ( mutation ) {
				if ( mutation.type === 'childList' ) {
					shouldScan = true;
				}
				if (
					mutation.type === 'attributes' &&
					( mutation.target === document.body || mutation.target === document.documentElement )
				) {
					shouldRenderTheme = true;
				}
			} );

			if ( shouldScan ) {
				scan();
			}
			if ( shouldRenderTheme ) {
				renderTheme();
			}
		} );
		observer.observe( document, {
			childList: true,
			subtree: true,
			attributes: true,
			attributeFilter: [ 'data-theme', 'class' ],
		} );

		if ( window.matchMedia ) {
			var media = window.matchMedia( '(prefers-color-scheme: dark)' );
			if ( media.addEventListener ) {
				media.addEventListener( 'change', renderTheme );
			} else if ( media.addListener ) {
				media.addListener( renderTheme );
			}
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', start );
	} else {
		start();
	}
} )();

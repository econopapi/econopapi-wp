( function () {
	function setActiveLink( links, activeId ) {
		links.forEach( function ( link ) {
			var matches = link.getAttribute( 'href' ) === '#' + activeId;
			link.classList.toggle( 'is-active', matches );
			if ( matches ) {
				link.setAttribute( 'aria-current', 'true' );
			} else {
				link.removeAttribute( 'aria-current' );
			}
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var sidebar = document.querySelector( '.eco-single-sidebar' );
		if ( ! sidebar ) {
			return;
		}

		var links = Array.prototype.slice.call( sidebar.querySelectorAll( '.eco-side-link[href^="#"]' ) );
		if ( links.length === 0 ) {
			return;
		}

		var sections = links
			.map( function ( link ) {
				var id = link.getAttribute( 'href' ).replace( '#', '' );
				return document.getElementById( id );
			} )
			.filter( Boolean );

		if ( sections.length === 0 ) {
			return;
		}

		links.forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				var id = link.getAttribute( 'href' ).replace( '#', '' );
				setActiveLink( links, id );
			} );
		} );

		if ( 'IntersectionObserver' in window ) {
			var observer = new IntersectionObserver(
				function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							setActiveLink( links, entry.target.id );
						}
					} );
				},
				{
					rootMargin: '-24% 0px -64% 0px',
					threshold: 0.01,
				}
			);

			sections.forEach( function ( section ) {
				observer.observe( section );
			} );
		}
	} );
} )();

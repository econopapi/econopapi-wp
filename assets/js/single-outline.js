( function () {
	function keepLinkVisible( link ) {
		if ( ! link || typeof link.scrollIntoView !== 'function' ) {
			return;
		}

		link.scrollIntoView( {
			block: 'nearest',
			inline: 'nearest',
			behavior: 'auto',
		} );
	}

	function setActiveLink( links, activeId ) {
		links.forEach( function ( link ) {
			var matches = link.getAttribute( 'href' ) === '#' + activeId;
			link.classList.toggle( 'is-active', matches );
			if ( matches ) {
				link.setAttribute( 'aria-current', 'true' );
				keepLinkVisible( link );
			} else {
				link.removeAttribute( 'aria-current' );
			}
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var sidebar = document.querySelector( '.eco-single-sidebar' );
		var singleProject = document.querySelector( '.eco-single--project' );
		var header = document.querySelector( '.eco-site-header' );
		var readingBar = document.querySelector( '[data-reading-bar]' );
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

		function updateProjectReadingState() {
			if ( ! singleProject || ! sections[0] || ! header ) {
				return;
			}

			var scrollY = window.scrollY || window.pageYOffset;
			var firstSectionTop = sections[0].getBoundingClientRect().top + scrollY;
			var readingBarHeight = readingBar && readingBar.classList.contains( 'is-visible' ) ? readingBar.offsetHeight : 0;
			var threshold = scrollY + header.offsetHeight + readingBarHeight + 18;

			singleProject.classList.toggle( 'is-outline-focused', threshold >= firstSectionTop );
		}

		window.addEventListener( 'scroll', updateProjectReadingState, { passive: true } );
		window.addEventListener( 'resize', updateProjectReadingState );
		window.addEventListener( 'load', updateProjectReadingState );
		updateProjectReadingState();

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

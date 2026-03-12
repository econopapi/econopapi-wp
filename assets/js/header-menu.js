( function () {
	var navSelector = '.eco-main-nav';
	var toggleSelector = '.eco-menu-toggle';
	var mobileBreakpoint = window.matchMedia( '(max-width: 921px)' );

	function closeMenu( nav, toggle ) {
		nav.classList.remove( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.setAttribute( 'aria-label', 'Abrir menú' );
		toggle.setAttribute( 'title', 'Abrir menú' );
	}

	function openMenu( nav, toggle ) {
		nav.classList.add( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		toggle.setAttribute( 'aria-label', 'Cerrar menú' );
		toggle.setAttribute( 'title', 'Cerrar menú' );
	}

	function bindMenu( nav ) {
		var toggle = nav.querySelector( toggleSelector );
		var panel = nav.querySelector( '.eco-main-nav__panel' );

		if ( ! toggle || ! panel ) {
			return;
		}

		toggle.addEventListener( 'click', function () {
			var isOpen = nav.classList.contains( 'is-open' );
			if ( isOpen ) {
				closeMenu( nav, toggle );
			} else {
				openMenu( nav, toggle );
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! mobileBreakpoint.matches ) {
				return;
			}

			if ( ! nav.contains( event.target ) ) {
				closeMenu( nav, toggle );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				closeMenu( nav, toggle );
			}
		} );

		window.addEventListener( 'resize', function () {
			if ( ! mobileBreakpoint.matches ) {
				closeMenu( nav, toggle );
			}
		} );

		panel.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				if ( mobileBreakpoint.matches ) {
					closeMenu( nav, toggle );
				}
			} );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( navSelector ).forEach( bindMenu );
	} );
} )();

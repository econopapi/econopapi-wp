( function () {
	var storageKey = 'econopapi-theme';
	var buttonSelector = '.eco-theme-toggle';

	function getPreferredTheme() {
		var savedTheme = null;
		try {
			savedTheme = localStorage.getItem( storageKey );
		} catch ( error ) {
			savedTheme = null;
		}

		if ( savedTheme === 'dark' || savedTheme === 'light' ) {
			return savedTheme;
		}

		if ( window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches ) {
			return 'dark';
		}

		return 'light';
	}

	function setThemeOnBody( theme ) {
		if ( ! document.body ) {
			return;
		}

		document.body.setAttribute( 'data-theme', theme );
		document.body.classList.toggle( 'theme-dark', theme === 'dark' );
	}

	function updateButtons( theme ) {
		var isDark = theme === 'dark';
		var nextThemeLabel = isDark ? 'Modo claro' : 'Modo oscuro';
		document.querySelectorAll( buttonSelector ).forEach( function ( button ) {
			button.setAttribute( 'aria-pressed', isDark ? 'true' : 'false' );
			button.setAttribute( 'aria-label', nextThemeLabel );
			button.setAttribute( 'title', nextThemeLabel );
			button.classList.toggle( 'is-dark', isDark );

			var textNode = button.querySelector( '.eco-theme-toggle__text' );
			if ( textNode ) {
				textNode.textContent = nextThemeLabel;
			}
		} );
	}

	function persistTheme( theme ) {
		try {
			localStorage.setItem( storageKey, theme );
		} catch ( error ) {
			return;
		}
	}

	function bindToggle(button) {
		button.addEventListener( 'click', function () {
			var currentTheme = document.body.getAttribute( 'data-theme' ) === 'dark' ? 'dark' : 'light';
			var nextTheme = currentTheme === 'dark' ? 'light' : 'dark';

			setThemeOnBody( nextTheme );
			updateButtons( nextTheme );
			persistTheme( nextTheme );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var initialTheme = getPreferredTheme();
		setThemeOnBody( initialTheme );
		updateButtons( initialTheme );
		document.querySelectorAll( buttonSelector ).forEach( bindToggle );
	} );
} )();

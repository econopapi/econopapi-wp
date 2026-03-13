( function () {
	function clamp( value, min, max ) {
		return Math.min( max, Math.max( min, value ) );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var readingBar = document.querySelector( '[data-reading-bar]' );
		if ( ! readingBar ) {
			return;
		}

		var hero = document.querySelector( '.eco-single-hero, .eco-page-hero' );
		var header = document.querySelector( '.eco-site-header' );
		var progress = readingBar.querySelector( '[data-reading-progress]' );
		var content = document.querySelector( '.eco-single-content, .eco-page-content' );
		var stickyOffsetAdjustment = 2;

		if ( ! hero || ! header || ! progress || ! content ) {
			return;
		}

		function updateOffsets() {
			var headerHeight = header.offsetHeight;
			var computedOffset = Math.max( 0, headerHeight - stickyOffsetAdjustment );
			document.documentElement.style.setProperty( '--eco-header-offset', computedOffset + 'px' );
		}

		function updateBarVisibility() {
			var headerHeight = header.offsetHeight;
			var heroBottom = hero.getBoundingClientRect().bottom;
			var isVisible = heroBottom <= headerHeight;
			readingBar.classList.toggle( 'is-visible', isVisible );

			if ( isVisible ) {
				document.documentElement.style.setProperty( '--eco-reading-bar-height', readingBar.offsetHeight + 'px' );
			} else {
				document.documentElement.style.setProperty( '--eco-reading-bar-height', '0px' );
			}
		}

		function updateProgress() {
			var scrollY = window.scrollY || window.pageYOffset;
			var contentTop = content.getBoundingClientRect().top + scrollY;
			var contentHeight = content.offsetHeight;
			var viewport = window.innerHeight || document.documentElement.clientHeight;

			var start = contentTop - header.offsetHeight - 40;
			var end = contentTop + contentHeight - viewport * 0.35;
			var ratio = end > start ? ( scrollY - start ) / ( end - start ) : 1;
			var bounded = clamp( ratio, 0, 1 );

			progress.style.transform = 'scaleX(' + bounded + ')';
		}

		function updateAll() {
			updateOffsets();
			updateBarVisibility();
			updateProgress();
		}

		window.addEventListener( 'scroll', updateAll, { passive: true } );
		window.addEventListener( 'resize', updateAll );
		window.addEventListener( 'load', updateAll );
		updateAll();
	} );
} )();

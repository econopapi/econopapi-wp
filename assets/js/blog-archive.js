(function () {
	var loadMoreButton = document.querySelector('.eco-blog-load-more');
	var postsGrid = document.getElementById('eco-blog-grid');
	var config = window.econopapiBlogArchive || null;

	if (!loadMoreButton || !postsGrid || !config || !config.ajaxUrl || !config.nonce) {
		return;
	}

	var pagination = loadMoreButton.closest('.eco-blog-pagination');
	var statusRegion = pagination ? pagination.querySelector('.eco-blog-pagination-status') : null;
	var isLoading = false;
	var defaultLabel = loadMoreButton.dataset.defaultLabel || loadMoreButton.textContent.trim();
	var loadingLabel = loadMoreButton.dataset.loadingLabel || (config.labels && config.labels.loading) || defaultLabel;

	function setStatus(message) {
		if (statusRegion) {
			statusRegion.textContent = message;
		}
	}

	function setLoadingState(enabled) {
		isLoading = enabled;
		loadMoreButton.classList.toggle('is-loading', enabled);
		loadMoreButton.setAttribute('aria-disabled', enabled ? 'true' : 'false');
		loadMoreButton.textContent = enabled ? loadingLabel : defaultLabel;
	}

	loadMoreButton.addEventListener('click', function (event) {
		var maxPages = parseInt(loadMoreButton.dataset.maxPages || config.maxPages || '1', 10);
		var currentPage = parseInt(loadMoreButton.dataset.currentPage || '1', 10);

		if (isLoading) {
			event.preventDefault();
			return;
		}

		if (currentPage >= maxPages) {
			return;
		}

		event.preventDefault();
		setLoadingState(true);

		var payload = new URLSearchParams();
		payload.set('action', 'econopapi_blog_archive_load_more');
		payload.set('nonce', String(config.nonce));
		payload.set('page', String(currentPage + 1));
		payload.set('category', String(loadMoreButton.dataset.category || config.category || ''));

		fetch(config.ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
			},
			body: payload.toString(),
		})
			.then(function (response) {
				if (!response.ok) {
					throw new Error('Network response was not ok.');
				}

				return response.json();
			})
			.then(function (result) {
				if (!result || !result.success || !result.data) {
					throw new Error('Invalid AJAX payload.');
				}

				if (result.data.html) {
					postsGrid.insertAdjacentHTML('beforeend', result.data.html);
				}

				loadMoreButton.dataset.currentPage = String(currentPage + 1);

				if (result.data.hasMore && result.data.nextUrl) {
					loadMoreButton.setAttribute('href', String(result.data.nextUrl));
					setStatus((config.labels && config.labels.loaded) || '');
					return;
				}

				setStatus((config.labels && config.labels.noMore) || '');
				if (pagination) {
					pagination.hidden = true;
				}
			})
			.catch(function () {
				setStatus((config.labels && config.labels.error) || '');
				window.location.assign(loadMoreButton.href);
			})
			.finally(function () {
				if (!pagination || !pagination.hidden) {
					setLoadingState(false);
				}
			});
	});
})();

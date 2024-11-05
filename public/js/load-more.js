function loadMore(button, url) {
    const currentPage = parseInt(button.getAttribute('data-page'));

    fetch(`${url}?page=${currentPage}`)
        .then(response => response.json())
        .then(data => {
            const containerId = button.classList.contains('load-more-replies')
                ? `replies-container-${data.commentId}`
                : 'comments-container';
            const container = document.getElementById(containerId);

            if (container) {
                container.insertAdjacentHTML('beforeend', data.comments || data.replies);
                convertTimes();
                button.setAttribute('data-page', currentPage + 1);

                if (!data.hasMore) {
                    button.style.display = 'none';
                }
            } else {
                console.error(`Container with ID '${containerId}' not found.`);
            }
        })
        .catch(error => console.error('Error loading more comments/replies:', error));
}

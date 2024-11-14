function loadMore(button, url) {
    const currentPage = parseInt(button.getAttribute('data-page'));

    // Collect all existing reply container IDs on the page
    const existingReplyIds = [];
    const replyContainers = document.querySelectorAll('[id^="replies-container-"]');
    replyContainers.forEach(container => {
        existingReplyIds.push(container.id.replace('replies-container-', ''));
    });
    console.log(existingReplyIds);

    // Send the current page and the existing reply IDs to the server
    fetch(`${url}?page=${currentPage}&existing_comment_ids=${JSON.stringify(existingReplyIds)}`)
        .then(response => response.json())
        .then(data => {
            const containerId = button.classList.contains('load-more-replies')
                ? `replies-container-${data.commentId}`
                : 'replies-container--1';

            const container = document.getElementById(containerId);

            if (container) {
                displayComments(container, data.comments || data.replies, button, currentPage, data.hasMore);
            } else {
                console.error(`Container with ID '${containerId}' not found.`);
            }
        })
        .catch(error => console.error('Error loading more comments/replies:', error));
}

function displayComments(container, content, button, currentPage, hasMore) {
    container.insertAdjacentHTML('beforeend', content);
    convertTimes();
    button.setAttribute('data-page', currentPage + 1);

    if (!hasMore) {
        button.style.display = 'none';
    }
}

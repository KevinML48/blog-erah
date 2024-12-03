let isFetching = false; // Initialize the isFetching variable to control multiple requests

function loadMore(button, url) {
    return new Promise((resolve, reject) => {
        const currentPage = parseInt(button.getAttribute('data-page'));

        // Find or create the loader
        let loader = button.nextElementSibling;
        if (!loader || !loader.classList.contains('loader')) {
            loader = document.createElement('div');
            loader.className = 'loader';
            button.parentNode.insertBefore(loader, button.nextSibling);
        }

        // Show the loader
        loader.classList.remove('hidden');
        button.disabled = true; // Disable the button while loading

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

                    // Hide the loader
                    loader.classList.add('hidden');
                    button.disabled = false; // Re-enable the button if necessary
                    resolve();
                } else {
                    console.error(`Container with ID '${containerId}' not found.`);

                    // Hide the loader on error
                    loader.classList.add('hidden');
                    button.disabled = false;
                    reject();
                }
            })
            .catch(error => {
                console.error('Error loading more comments/replies:', error);

                // Hide the loader and re-enable the button on error
                loader.classList.add('hidden');
                button.disabled = false;
                reject(error);
            });
    });
}

function displayComments(container, content, button, currentPage, hasMore) {
    container.insertAdjacentHTML('beforeend', content);
    convertTimes();
    button.setAttribute('data-page', currentPage + 1);

    if (!hasMore) {
        button.style.display = 'none';
    }
}

// Function to trigger the existing loadMore function
function triggerLoadMore(button) {
    const url = button.getAttribute('data-url');
    return loadMore(button, url); // Return the promise from loadMore
}

// Check if the user is at the bottom of the page
window.onscroll = async function () {
    // Prevent multiple triggers by checking the isFetching flag
    if (isFetching) return;

    // When the user scrolls to the bottom of the page
    if (document.documentElement.scrollTop + window.innerHeight >= document.documentElement.scrollHeight) {
        const loadMoreButton = document.getElementById('load-more');
        const loader = document.getElementById('loader'); // The loader element

        // Only trigger loadMore if there are more pages (button is in the DOM)
        if (loadMoreButton && loadMoreButton.style.display !== 'none') {
            if (loader) loader.classList.remove('hidden');
            isFetching = true; // Set isFetching to true to prevent further requests

            try {
                await triggerLoadMore(loadMoreButton); // Wait for loading to complete
            } catch (error) {
                console.error('Error while loading more content:', error);
            } finally {
                isFetching = false; // Set isFetching to false when done
                if (loader) loader.classList.add('hidden');
            }
        }
    }
};

let loading = false;
// Initialize a global `pages` object to track the page and has_more_pages for each section
window.pages = {
    comments: { page: 1, has_more_pages: true }, // Example: { page: 1, has_more_pages: true }
    likes: { page: 1, has_more_pages: true },
    'post-likes': { page: 1, has_more_pages: true }
};

// Generic function to load more data, now with separate page tracking for each section
function loadMoreData(url, sectionId, pageKey) {
    const loader = document.getElementById(`loader-${sectionId}`);
    if (loading || !window.pages[pageKey].has_more_pages) return;  // Stop if no more pages
    loading = true;
    loader.style.display = "block"; // Show loading indicator

    // Fetch data using the page number from the `pages` object
    const page = window.pages[pageKey].page; // Use the page for the specific section
    fetch(`${url}?page=${page + 1}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            const section = document.getElementById(sectionId); // Find the section to append data
            section.innerHTML += data.content; // Append the new data to the section
            convertTimes();

            // Update the page number and `has_more_pages` for the section
            if (data.has_more_pages) {
                window.pages[pageKey].page++; // Increment the page for the section
            } else {
                window.pages[pageKey].has_more_pages = false; // No more pages
            }

            loader.style.display = "none"; // Hide the loader
            loading = false; // Reset loading state
        })
        .catch(error => {
            console.error('Error loading data:', error);
            loader.style.display = "none"; // Hide loader on error
            loading = false;
        });
}

// Specific function for loading more comments (will use loadMoreData)
function profileLoadMoreComments(url) {
    const pageKey = 'comments'; // The key for the comments section in window.pages
    if (!window.pages[pageKey].has_more_pages) return;  // If no more pages, stop immediately

    loadMoreData(url, 'comments-container', pageKey);
}


// Function to load more liked comments
function profileLoadMoreLikedComments(url) {
    const pageKey = 'likes'; // The key for the likes section in window.pages
    if (!window.pages[pageKey].has_more_pages) return;  // If no more pages, stop immediately

    loadMoreData(url, 'likes-container', pageKey);
}


// Function to load more liked posts
function profileLoadMoreLikedPosts(url) {
    const pageKey = 'post-likes'; // The key for the post-likes section in window.pages
    if (!window.pages[pageKey].has_more_pages) return;  // If no more pages, stop immediately

    loadMoreData(url, 'posts-container', pageKey);
}


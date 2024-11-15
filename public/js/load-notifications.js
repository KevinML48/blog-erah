document.addEventListener("DOMContentLoaded", function() {
    let page = 1; // Keep track of the current page
    let loading = false; // Prevent multiple requests at once
    const loader = document.getElementById('loader'); // Loader element
    const notificationsContainer = document.getElementById('notifications-container'); // Notification container

    function loadMoreNotifications() {
        if (loading) return;
        loading = true;
        loader.style.display = "block";


        fetch(`/notifications?page=${page + 1}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {

                notificationsContainer.innerHTML += data.notifications;


                if (!data.next_page_url) {
                    window.removeEventListener('scroll', handleScroll);
                }


                page++;
                loader.style.display = "none";
                loading = false;
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                loader.style.display = "none";
                loading = false;
            });
    }

    function handleScroll() {
        if (window.innerHeight + window.scrollY >= document.body.scrollHeight - 500) {
            loadMoreNotifications();
        }
    }


    window.addEventListener('scroll', handleScroll);
});

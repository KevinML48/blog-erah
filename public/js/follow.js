function followUser(userId) {
    fetch(`/follow/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                toggleFollowButtons(userId, true);
                console.log(data.message);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function unfollowUser(userId) {
    fetch(`/unfollow/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                toggleFollowButtons(userId, false);
                console.log(data.message);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function toggleFollowButtons(userId, isFollowing) {
    const followButton = document.getElementById(`follow-button-${userId}`);
    const unfollowButton = document.getElementById(`unfollow-button-${userId}`);

    if (isFollowing) {
        followButton.classList.add('hidden');
        unfollowButton.classList.remove('hidden');
    } else {
        followButton.classList.remove('hidden');
        unfollowButton.classList.add('hidden');
    }
}

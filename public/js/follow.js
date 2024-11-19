function handleFetch(url, buttonId, toggleAction) {
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                toggleAction(buttonId);
                console.log(data.message);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function followUser(userId) {
    handleFetch(`/follow/${userId}`, userId, () => toggleFollowButtons(userId, true));
}

function unfollowUser(userId) {
    handleFetch(`/unfollow/${userId}`, userId, () => toggleFollowButtons(userId, false));
}

function muteComment(contentId) {
    handleFetch(`/notifications/preferences/mute/${contentId}`, contentId, () => toggleMuteButtons(contentId, true));
}

function unmuteComment(contentId) {
    handleFetch(`/notifications/preferences/unmute/${contentId}`, contentId, () => toggleMuteButtons(contentId, false));
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

function toggleMuteButtons(contentId, hasMuted) {
    console.log('toggleMuteButtons');
    const muteButton = document.getElementById(`mute-button-${contentId}`);
    const unmuteButton = document.getElementById(`unmute-button-${contentId}`);

    if (hasMuted) {
        muteButton.classList.add('hidden');
        unmuteButton.classList.remove('hidden');
    } else {
        muteButton.classList.remove('hidden');
        unmuteButton.classList.add('hidden');
    }
}


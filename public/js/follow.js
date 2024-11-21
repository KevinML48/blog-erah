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
    const followButtons = document.querySelectorAll(`.follow-button-${userId}, .simple-follow-button-${userId}, .detailed-follow-button-${userId}`);
    const unfollowButtons = document.querySelectorAll(`.unfollow-button-${userId}, .simple-unfollow-button-${userId}, .detailed-unfollow-button-${userId}`);

    followButtons.forEach(followButton => {
        if (isFollowing) {
            followButton.classList.add('hidden');
        } else {
            followButton.classList.remove('hidden');
        }
    });

    unfollowButtons.forEach(unfollowButton => {
        if (isFollowing) {
            unfollowButton.classList.remove('hidden');
        } else {
            unfollowButton.classList.add('hidden');
        }
    });
}

function toggleMuteButtons(contentId, hasMuted) {
    console.log('toggleMuteButtons');
    const muteButtons = document.querySelectorAll(`.simple-mute-button-${contentId}, .detailed-mute-button-${contentId}`);
    const unmuteButtons = document.querySelectorAll(`.simple-unmute-button-${contentId}, .detailed-unmute-button-${contentId}`);

    muteButtons.forEach(muteButton => {
        if (hasMuted) {
            muteButton.classList.add('hidden');
        } else {
            muteButton.classList.remove('hidden');
        }
    });

    unmuteButtons.forEach(unmuteButton => {
        if (hasMuted) {
            unmuteButton.classList.remove('hidden');
        } else {
            unmuteButton.classList.add('hidden');
        }
    });
}


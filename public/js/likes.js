function toggleLike(id, type, action) {
    const method = action === 'like' ? 'POST' : 'DELETE';
    const url = `/${type}s/${id}/${action}`;

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok.');
        })
        .then(data => {
            updateLikeUI(id, type, action, data.likes_count);
        })
        .catch(error => {
            console.error(`Error ${action === 'like' ? 'liking' : 'unliking'} ${type}:`, error);
        });
}

function updateLikeUI(id, type, action, likesCount) {
    const likeButton = document.querySelector(`#like-${type}-button-${id}`);
    const unlikeButton = document.querySelector(`#unlike-${type}-button-${id}`);
    const likesCountElement = document.querySelector(`#likes-${type}-count-${id}`);

    if (action === 'like') {
        likeButton.classList.add('hidden');
        unlikeButton.classList.remove('hidden');
    } else {
        likeButton.classList.remove('hidden');
        unlikeButton.classList.add('hidden');
    }

    updateLikesCount(likesCountElement, likesCount);
}

function likeComment(commentId) {
    toggleLike(commentId, 'comment', 'like');
}

function unlikeComment(commentId) {
    toggleLike(commentId, 'comment', 'unlike');
}

function likePost(postId) {
    toggleLike(postId, 'post', 'like');
}

function unlikePost(postId) {
    toggleLike(postId, 'post', 'unlike');
}

function updateLikesCount(likesCountElement, likesCount) {
    likesCountElement.innerText = likesCount > 0 ? `(${likesCount})` : '';
    likesCountElement.style.display = likesCount > 0 ? 'inline' : 'none';
}

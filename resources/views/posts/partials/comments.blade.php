<div class="mt-6">
    @if (request()->routeIs('posts.show'))
        <h4 class="font-semibold text-lg">Commentaires ({{ $totalCommentsCount }})</h4>

        <!-- Comment Form -->
        <div class="mb-4">
            @include('posts.partials.comment-form', ['parentId' => -1, 'post' => $post,])
        </div>
    @endif

    <!-- Displaying Comments -->
    @if (request()->routeIs('comments.show'))
        <div class="mb-4">
            <!-- Link back to the full post view -->
            <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600">
                ← Retour à la discussion complète
            </a>

            <!-- Link to the parent comment, if one exists -->
            @if ($comment->parent_id)
                <a href="{{ route('comments.show', ['post' => $post->id, 'comment' => $comment->parent_id]) }}"
                   class="text-blue-600 ml-4">
                    ← Retour au commentaire parent
                </a>
            @endif
        </div>
    @endif
        <div class="mt-4" id="comments-container">
            @foreach ($comments as $comment)
                @include('posts.partials.comment', ['comment' => $comment, 'depth' => 0])
            @endforeach
        </div>


    @if ($comments->hasMorePages())
        <button id="load-more" data-url="{{ route('comments.loadMore', ['post' => $post->id]) }}"
                data-page="{{ $comments->currentPage() }}">
            Charger plus de commentaires
        </button>
    @endif
</div>

<script>
    document.getElementById('load-more').addEventListener('click', function () {
        const button = this;
        const url = button.getAttribute('data-url');
        const currentPage = parseInt(button.getAttribute('data-page'));

        fetch(`${url}?page=${currentPage}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('comments-container').insertAdjacentHTML('beforeend', data.comments);

                convertTimes();

                button.setAttribute('data-page', currentPage + 1);

                if (!data.hasMore) {
                    button.style.display = 'none';
                }
            });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.load-more-replies').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                const currentReplyPage = parseInt(this.getAttribute('data-page'));

                fetch(`${url}?page=${currentReplyPage}`)
                    .then(response => response.json())
                    .then(data => {
                        const repliesContainer = document.getElementById(`replies-container-${data.commentId}`);

                        if (repliesContainer) {
                            repliesContainer.insertAdjacentHTML('beforeend', data.replies);

                            convertTimes();

                            this.setAttribute('data-page', currentReplyPage + 1);

                            if (!data.hasMore) {
                                this.style.display = 'none';
                            }
                        } else {
                            console.error(`Replies container with ID 'replies-container-${data.commentId}' not found.`);
                        }
                    })
                    .catch(error => console.error('Error loading more replies:', error));
            });
        });
    });
</script>


<script>
    function toggleModal(parentId) {
        const modal = document.getElementById('searchModal');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
        document.getElementById('gifResults').innerHTML = ''; // Clear results on close

        // Store the parentId for later use
        modal.dataset.parentId = parentId; // Use data attribute to store the parentId
    }

    function performSearch() {
        const query = document.getElementById('searchQuery').value;
        if (!query) {
            alert('Please enter a search term');
            return;
        }

        // Fetch results from Laravel route
        fetch(`/tenor/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                displayGIFs(data.results); // Call function to display GIFs
            })
            .catch(error => {
                console.error('Error fetching Tenor data:', error);
            });
    }

    function displayGIFs(gifs) {
        const gifResults = document.getElementById('gifResults');
        gifResults.innerHTML = ''; // Clear previous results

        gifs.forEach(gif => {
            const gifElement = document.createElement('img');
            gifElement.src = gif.media_formats.gif.url;
            gifElement.alt = gif.content_description;
            gifElement.classList.add('cursor-pointer', 'rounded-md');
            gifElement.onclick = () => {
                const parentId = document.getElementById('searchModal').dataset.parentId; // Get the parentId
                selectGIF(gif.media_formats.gif.url, parentId);
            };
            gifResults.appendChild(gifElement);
        });
    }

    function selectGIF(url, parentId) {
        // Set the URL in the hidden input field
        document.getElementById(`gifUrl-${parentId}`).value = url;

        // Display the selected GIF
        const selectedGifContainer = document.getElementById(`selectedGifContainer-${parentId}`);
        const selectedGif = document.getElementById(`selectedGif-${parentId}`);
        selectedGif.src = url;
        selectedGifContainer.classList.remove('hidden'); // Show the container

        // Hide the media upload input
        const mediaUpload = document.getElementById(`mediaUpload-${parentId}`);
        mediaUpload.classList.add('hidden');

        // Show the cancel button
        const cancelButton = document.getElementById(`cancelButton-${parentId}`);
        cancelButton.classList.remove('hidden');

        // Close the modal
        toggleModal(parentId);
    }

    function unselectGIF(parentId) {
        // Clear the GIF URL input
        document.getElementById(`gifUrl-${parentId}`).value = '';

        // Hide the selected GIF container
        const selectedGifContainer = document.getElementById(`selectedGifContainer-${parentId}`);
        selectedGifContainer.classList.add('hidden');

        // Reappear the media upload input
        const mediaUpload = document.getElementById(`mediaUpload-${parentId}`);
        mediaUpload.classList.remove('hidden');

        // Hide the cancel button
        const cancelButton = document.getElementById(`cancelButton-${parentId}`);
        cancelButton.classList.add('hidden');
    }
</script>
<script>
    document.querySelectorAll('[id^="commentBody-"]').forEach(commentBody => {
        const parentId = commentBody.id.split('-')[1]; // Extract parentId from the commentBody id
        const currentCount = document.getElementById(`current-${parentId}`);

        commentBody.addEventListener('input', function () {
            currentCount.textContent = commentBody.value.length;
        });
    });
</script>

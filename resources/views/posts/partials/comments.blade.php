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
        modal.dataset.parentId = parentId;
    }

    function performSearch() {
        const query = document.getElementById('searchQuery').value;
        if (!query) {
            alert('Please enter a search term');
            return;
        }

        fetch(`/tenor/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                displayGIFs(data.results);
            })
            .catch(error => {
                console.error('Error fetching Tenor data:', error);
            });
    }

    function displayGIFs(gifs) {
        const gifResults = document.getElementById('gifResults');
        gifResults.innerHTML = '';

        gifs.forEach(gif => {
            const gifElement = document.createElement('img');
            gifElement.src = gif.media_formats.gif.url;
            gifElement.alt = gif.content_description;
            gifElement.classList.add('cursor-pointer', 'rounded-md');
            gifElement.onclick = () => {
                const parentId = document.getElementById('searchModal').dataset.parentId;
                selectGIF(gif.media_formats.gif.url, parentId);
            };
            gifResults.appendChild(gifElement);
        });
    }

    function previewImage(parentId) {
        const fileInput = document.getElementById(`media-${parentId}`);
        const displayZone = document.getElementById(`displayMediaZone-${parentId}`);
        const selectedImage = document.getElementById(`selectedImage-${parentId}`);
        const selectedGif = document.getElementById(`selectedGif-${parentId}`);

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                selectedImage.src = e.target.result;
                displayZone.classList.remove('hidden');
                selectedImage.classList.remove('hidden');
                selectedGif.classList.add('hidden');
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    function selectGIF(url, parentId) {
        document.getElementById(`gifUrl-${parentId}`).value = url;
        const displayZone = document.getElementById(`displayMediaZone-${parentId}`);
        const selectedGif = document.getElementById(`selectedGif-${parentId}`);
        const selectedImage = document.getElementById(`selectedImage-${parentId}`);

        selectedGif.src = url;
        displayZone.classList.remove('hidden');
        selectedGif.classList.remove('hidden');
        selectedImage.classList.add('hidden');
        document.getElementById(`mediaUpload-${parentId}`).classList.add('hidden');
        toggleModal(parentId);
    }

    function clearMedia(parentId) {
        // Clear the GIF URL input
        document.getElementById(`gifUrl-${parentId}`).value = '';

        // Hide the selected image and GIF containers
        const displayZone = document.getElementById(`displayMediaZone-${parentId}`);
        const selectedImage = document.getElementById(`selectedImage-${parentId}`);
        const selectedGif = document.getElementById(`selectedGif-${parentId}`);

        selectedImage.classList.add('hidden');
        selectedGif.classList.add('hidden');
        displayZone.classList.add('hidden');

        // Reset the file input
        const fileInput = document.getElementById(`media-${parentId}`);
        fileInput.value = ''; // Clear the file input

        // Reappear the media upload input
        document.getElementById(`mediaUpload-${parentId}`).classList.remove('hidden');
    }


    // Function to handle the paste event
    function handlePaste(event, parentId) {
        const clipboardData = event.clipboardData || window.clipboardData;
        const items = clipboardData.items;
        let imageFound = false;

        // Loop through clipboard items to find images
        for (let i = 0; i < items.length; i++) {
            const item = items[i];

            // Check if the pasted item is an image
            if (item.kind === 'file' && item.type.startsWith('image/')) {
                event.preventDefault(); // Prevent the default paste behavior for images
                imageFound = true;

                const file = item.getAsFile();
                const fileInput = document.getElementById(`media-${parentId}`);
                const dataTransfer = new DataTransfer();

                // Add the file to the DataTransfer
                dataTransfer.items.add(file);

                // Set the file input's files property
                fileInput.files = dataTransfer.files;

                // Call the previewImage function
                previewImage(parentId);
                break; // Exit the loop after finding the first image
            }
        }

        // Allow normal paste behavior if no image is found
        if (!imageFound) {
            return; // let the event continue, allowing the default paste
        }
    }

    // Function to update hidden input field with content
    function updateHiddenInput(parentId) {
        const commentBody = document.getElementById(`commentBody-${parentId}`);
        const commentInput = document.getElementById(`commentInput-${parentId}`);
        commentInput.value = commentBody.innerHTML; // Store HTML content
    }

    // Function to initialize event listeners
    function initEventListeners() {
        // Select all contenteditable divs
        const commentAreas = document.querySelectorAll('[contenteditable="true"]');

        commentAreas.forEach((commentArea) => {
            const parentId = commentArea.dataset.parentId; // Get parentId from data attribute

            commentArea.addEventListener('paste', (event) => handlePaste(event, parentId));
            commentArea.addEventListener('input', () => updateHiddenInput(parentId)); // Update on input
        });
    }

    // Call this function to initialize event listeners for all editable divs
    initEventListeners();


</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Select all content-editable divs
        document.querySelectorAll('[contenteditable="true"]').forEach(commentBody => {
            const parentId = commentBody.dataset.parentId; // Get parentId from data attribute
            const currentCount = document.getElementById(`current-${parentId}`);

            // Check if currentCount exists to avoid null reference errors
            if (currentCount) {
                commentBody.addEventListener('input', function () {
                    // Get the length of the inner text, removing <br> tags for accurate count
                    const textContent = commentBody.innerText.replace(/^\s*<br\s*\/?>\s*|\s*<br\s*\/?>\s*$/g, '').length;
                    currentCount.textContent = textContent; // Update the character count
                });
            } else {
                console.warn(`Element with id "current-${parentId}" not found.`);
            }
        });
    });
</script>

<script>
    // function handleSubmit(event, parentId) {
    //     // Prevent the default form submission
    //     event.preventDefault();
    //
    //     // Get the content from the editable div
    //     const commentBody = document.getElementById(`commentBody-${parentId}`).innerText;
    //
    //     // Set the content to the hidden input
    //     document.getElementById(`commentInput-${parentId}`).value = commentBody;
    //
    //     // Optionally, submit the form programmatically if you want to continue with the submission
    //     document.getElementById(`commentForm-${parentId}`).submit();
    // }
</script>

<div class="mt-6 comment-section">
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

@if ($comments->hasMorePages())
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
@endif
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
        clearMedia(parentId)
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
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize event listeners for existing content-editable divs
        function initEventListeners() {
            const commentAreas = document.querySelectorAll('[contenteditable="true"]');

            commentAreas.forEach((commentArea) => {
                const parentId = commentArea.dataset.parentId; // Get parentId from data attribute

                // Attach event listeners for existing elements
                commentArea.addEventListener('paste', (event) => handlePaste(event, parentId));
                commentArea.addEventListener('input', () => updateHiddenInput(parentId)); // Update on input
            });
        }

        // Call the initialization function for existing inputs
        initEventListeners();

        // Event delegation for dynamically added content-editable divs
        document.addEventListener('paste', function (event) {
            if (event.target.matches('[contenteditable="true"]')) {
                const parentId = event.target.dataset.parentId; // Get parentId from data attribute
                handlePaste(event, parentId); // Call your handlePaste function
            }
        });

        document.addEventListener('input', function (event) {
            if (event.target.matches('[contenteditable="true"]')) {
                const parentId = event.target.dataset.parentId; // Get parentId from data attribute
                updateHiddenInput(parentId); // Call your updateHiddenInput function
            }
        });
    });



</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Find all editable divs with the class "comment-body"
        const commentBodies = document.querySelectorAll(".comment-body");
        commentBodies.forEach(function (commentBody) {
            // Get the parent ID from the data attribute
            const parentId = commentBody.getAttribute("data-parent-id");
            const commentInput = document.getElementById(`commentInput-${parentId}`);

            // Check if the hidden input has a value
            if (commentInput && commentInput.value) {
                // Set the editable div's content to the hidden input's value
                commentBody.innerHTML = commentInput.value;
                if (parentId > 0) {
                    showReplyForm(parentId);
                }
                updateCounter(parentId);
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Function to initialize comment inputs
        function initializeCommentInputs() {
            // Select all content-editable divs
            document.querySelectorAll('[contenteditable="true"]').forEach(commentBody => {
                const parentId = commentBody.dataset.parentId; // Get parentId from data attribute
                const hiddenInput = document.getElementById(`commentInput-${parentId}`);

                // Set initial values
                updateCounter(parentId);
                updateHiddenInput(parentId);

                // Add an input event listener to update the counter and hidden input as the user types
                commentBody.addEventListener('input', function () {
                    updateCounter(parentId);
                    updateHiddenInput(parentId);
                });
            });
        }

        // Call the function initially to set up existing inputs
        initializeCommentInputs();

        // Event delegation for dynamically added content-editable divs
        document.addEventListener('input', function (event) {
            if (event.target.matches('[contenteditable="true"]')) {
                const parentId = event.target.dataset.parentId; // Get parentId from data attribute
                updateCounter(parentId);
                updateHiddenInput(parentId);
            }
        });
    });


    // Function to update the character count
    function updateCounter(parentId) {
        const commentBody = document.getElementById(`commentBody-${parentId}`);
        const currentCount = document.getElementById(`current-${parentId}`);

        if (commentBody && currentCount) {
            // Count characters by text content only (ignoring HTML)
            const textContentLength = commentBody.innerText.length;
            currentCount.textContent = textContentLength;
        }
    }

    // Function to update the hidden input without extra HTML tags
    function updateHiddenInput(parentId) {
        const commentBody = document.getElementById(`commentBody-${parentId}`);
        const hiddenInput = document.getElementById(`commentInput-${parentId}`);

        if (commentBody && hiddenInput) {
            // Use textContent to get plain text, converting newlines properly
            const plainText = commentBody.innerText.replace(/\n/g, '\n');
            hiddenInput.value = plainText;
        }
    }


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

@if (request()->routeIs('comments.show'))
<script>
    window.onload = function() {
        const targetElement = document.querySelector('.comment-section');
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });
        }
    };
</script>
@endif

document.addEventListener("DOMContentLoaded", function () {
    // Function to initialize listeners
    function initEventListeners() {
        const commentAreas = document.querySelectorAll('[contenteditable="true"]');

        commentAreas.forEach((commentArea) => {
            const parentId = commentArea.dataset.parentId; // Get parentId from data attribute
            const hiddenInput = document.getElementById(`commentInput-${parentId}`);

            // Set initial values for counter and hidden input
            if (hiddenInput && hiddenInput.value) {
                commentArea.innerHTML = hiddenInput.value;
                if (parentId > 0) showReplyForm(parentId);
            }
            updateCounter(parentId);
            updateHiddenInput(parentId);

            // Attach input and paste listeners to update counter and hidden input as the user types/pastes
            commentArea.addEventListener('input', () => {
                updateCounter(parentId);
                updateHiddenInput(parentId);
            });
            commentArea.addEventListener('paste', (event) => {
                handlePaste(event, parentId);
                updateHiddenInput(parentId);
                updateCounter(parentId);
            });
        });
    }

    // Initialize listeners for existing content-editable divs
    initEventListeners();

    // Event delegation for dynamically added content-editable divs
    document.addEventListener('input', function (event) {
        if (event.target.matches('[contenteditable="true"]')) {
            const parentId = event.target.dataset.parentId;
            updateCounter(parentId);
            updateHiddenInput(parentId);
        }
    });

    document.addEventListener('paste', function (event) {
        if (event.target.matches('[contenteditable="true"]')) {
            const parentId = event.target.dataset.parentId;
            handlePaste(event, parentId);
            updateHiddenInput(parentId);
            updateCounter(parentId);
        }
    });
});


function toggleReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    replyForm.classList.toggle('hidden');
}

function showReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    replyForm.classList.remove('hidden');
}


// Function to update the character count
function updateCounter(parentId) {
    const commentInput = document.getElementById(`commentInput-${parentId}`);
    const currentCount = document.getElementById(`current-${parentId}`);

    if (commentInput && currentCount) {
        // Count characters by text content only (ignoring HTML)
        const textContentLength = commentInput.value.length;
        currentCount.textContent = textContentLength;
    }
}

// Function to update the hidden input without extra HTML tags
function updateHiddenInput(parentId) {
    const commentBody = document.getElementById(`commentBody-${parentId}`);
    const hiddenInput = document.getElementById(`commentInput-${parentId}`);

    if (commentBody && hiddenInput) {
        // Use textContent to get plain text, converting newlines properly
        const plainText = commentBody.innerText.replace(/\n/g, '\n').trim();
        hiddenInput.value = plainText;
    }
}


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

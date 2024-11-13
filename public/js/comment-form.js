document.addEventListener("DOMContentLoaded", function () {
    // Function to initialize listeners on the initial contenteditable divs
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

    // Event delegation for dynamically added contenteditable divs
    document.addEventListener('input', function (event) {
        if (event.target.matches('[contenteditable="true"]')) {
            const parentId = event.target.dataset.parentId;
            if (parentId) {
                updateCounter(parentId);
                updateHiddenInput(parentId);
            }
        }
    });

    document.addEventListener('paste', function (event) {
        if (event.target.matches('[contenteditable="true"]')) {
            const parentId = event.target.dataset.parentId;
            if (parentId) {
                handlePaste(event, parentId);
                updateHiddenInput(parentId);
                updateCounter(parentId);
            }
        }
    });

    showReplyForm(-1);
    // Initialize listeners for existing content-editable divs when the page loads
    initEventListeners();
});



// Function to toggle the reply form visibility
function toggleReplyForm(commentId) {
    const formContainer = document.getElementById(`form-container-${commentId}`);

    // Check if the form already exists
    const form = formContainer.querySelector('form');
    if (!form) {
        // If the form is not yet loaded, load and show it
        showReplyForm(commentId);
    } else {
        // If the form is already there, toggle visibility
        formContainer.classList.toggle('hidden');
    }
}

// Function to show the reply form
function showReplyForm(commentId) {
    console.log(commentId);

    const formContainer = document.getElementById(`form-container-${commentId}`);

    // Check if the form already exists
    if (formContainer.querySelector('form')) {
        console.log('Form already exists');
        return; // If the form already exists, don't clone it again
    }

    // Get the form template from the hidden template
    const formTemplate = document.getElementById('reply-form-template');

    // Clone the form template
    const clonedForm = formTemplate.content.cloneNode(true);  // Use content to avoid unnecessary extra wrapper div

    // Update form IDs and names dynamically for the comment
    const form = clonedForm.querySelector('form');
    form.id = `commentForm-${commentId}`;

    // Update commentBody and commentInput IDs dynamically
    const commentBody = clonedForm.querySelector('#commentBody');
    commentBody.id = `commentBody-${commentId}`;
    commentBody.dataset.parentId = commentId;

    const commentInput = clonedForm.querySelector('#commentInput');
    commentInput.id = `commentInput-${commentId}`;
    commentInput.name = `input-body-${commentId}`;

    const mediaUpload = clonedForm.querySelector('#mediaUpload');
    mediaUpload.id = `mediaUpload-${commentId}`;

    const mediaLabel = clonedForm.querySelector('#media-label');
    mediaLabel.id = `media-label-${commentId}`;
    mediaLabel.setAttribute('for', `media-${commentId}`);

    const mediaInput = clonedForm.querySelector('#media');
    mediaInput.id = `media-${commentId}`;
    mediaInput.setAttribute('onchange', `previewImage(${commentId})`);

    const displayMediaZone = clonedForm.querySelector('#displayMediaZone');
    displayMediaZone.id = `displayMediaZone-${commentId}`;

    const gifButton = clonedForm.querySelector('#gifButton');
    gifButton.id = `gifButton-${commentId}`; // Dynamically set the button's ID
    gifButton.setAttribute('onclick', `toggleModal(${commentId})`);

    const selectedImage = clonedForm.querySelector('#selectedImage');
    selectedImage.id = `selectedImage-${commentId}`;

    const selectedGif = clonedForm.querySelector('#selectedGif');
    selectedGif.id = `selectedGif-${commentId}`;

    const cancelButton = clonedForm.querySelector('#cancelButton');
    cancelButton.id = `cancelButton-${commentId}`;
    cancelButton.setAttribute('onclick', `clearMedia(${commentId})`);

    const gifUrl = clonedForm.querySelector('#gifUrl');
    gifUrl.id = `gifUrl-${commentId}`;

    const counterSpan = clonedForm.querySelector('#current');
    counterSpan.id = `current-${commentId}`;

    // Set the parent_id field to the current commentId
    const parentIdInput = clonedForm.querySelector('input[name="parent_id"]');
    parentIdInput.value = commentId;

    // Append the cloned form to the container
    formContainer.appendChild(clonedForm);

    // Optionally, scroll to the form if you want a smooth experience
    clonedForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
    event.preventDefault();
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
    event.preventDefault();
    console.log('tick')
    console.log(parentId)
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
    event.preventDefault();
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

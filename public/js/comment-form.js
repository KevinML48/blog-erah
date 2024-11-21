document.addEventListener("DOMContentLoaded", function () {
    // Function to initialize listeners for textarea elements
    function initEventListeners() {
        const commentAreas = document.querySelectorAll('textarea[data-parent-id]');
        commentAreas.forEach((commentArea) => {
            const parentId = commentArea.dataset.parentId;

            // Update counter on input
            commentArea.addEventListener('input', () => {
                updateCounter(parentId);
            });

            // Optionally handle paste if needed
            commentArea.addEventListener('paste', (event) => {
                updateCounter(parentId);
            });
        });
    }

    // Automatically show the form with ID -1 on page load
    const formContainer = document.querySelector('#form-container'); // Your selector

    // Check if formContainer is not null
    if (formContainer !== null) {
        showReplyForm(-1);
    }

    // Initialize listeners for existing textareas
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
    const formContainer = document.getElementById(`form-container-${commentId}`);

    // Check if the form already exists
    if (formContainer.querySelector('form')) {
        return; // If the form already exists, don't clone it again
    }

    // Get the form template from the hidden template
    const formTemplate = document.getElementById('reply-form-template');

    // Clone the form template
    const clonedForm = formTemplate.content.cloneNode(true);

    // Update form IDs and names dynamically for the comment
    const form = clonedForm.querySelector('form');
    form.id = `commentForm-${commentId}`;

    // Update textarea ID dynamically
    const commentInput = clonedForm.querySelector('#commentInput');
    commentInput.id = `commentInput-${commentId}`;
    commentInput.name = `input-body-${commentId}`;
    commentInput.dataset.parentId = commentId;

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
    gifButton.id = `gifButton-${commentId}`;
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

    commentInput.addEventListener('input', () => {
        updateCounter(commentId);
    });

    commentInput.addEventListener('paste', (event) => {
        updateCounter(commentId);
    });
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

document.addEventListener('submit', function (event) {
    if (event.target.matches('form[id^="commentForm-"]')) {
        event.preventDefault(); // Prevent the form from submitting the normal way

        const form = event.target;
        const formId = form.id;
        const formData = new FormData(form);

        fetch(form.action, {  // Use the form's action attribute for the URL
            method: form.method, // Use the form's method (POST/PUT, etc.)
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    // Check if the response status is 413 (Payload Too Large)
                    if (response.status === 413) {
                        // Display a specific error for file size limit
                        displayFileSizeError(form);
                        return;
                    }
                    // If the response status is another error (e.g., 422 for validation)
                    return response.json().then(data => {
                        if (data.errors) {
                            // If there are validation errors, display them
                            displayValidationErrors(data.errors, formId);
                        }
                    });
                }

                return response.json(); // If the response is okay, continue parsing JSON
            })
            .then(data => {
                if (data.comment) {
                    // If successful, insert the new comment into the replies container
                    addCommentToReplies(data.comment, formId);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
});

function addCommentToReplies(commentHtml, formId) {
    const repliesContainer = document.getElementById(`replies-container-${formId.replace('commentForm-', '')}`);
    if (repliesContainer) {
        repliesContainer.insertAdjacentHTML('afterbegin', commentHtml);
    }
    // Remove the submitted form entirely
    const form = document.getElementById(formId);
    if (form) {
        form.remove(); // Remove the form element from the DOM
    }

    convertTimes()
}


function displayFileSizeError(form) {
    const errorContainer = form.querySelector('.error-messages') || createErrorContainer(form);

    // Clear previous errors
    errorContainer.innerHTML = '';

    // Create and append the file size error message
    const errorElement = document.createElement('p');
    errorElement.classList.add('error-message');
    errorElement.textContent = 'Le fichier téléchargé dépasse la limite de taille du serveur (2 Mo). Veuillez télécharger un fichier plus petit.';
    errorContainer.appendChild(errorElement);
}

function displayValidationErrors(errors, formId) {
    const form = document.getElementById(formId);
    const errorContainer = form.querySelector('.error-messages') || createErrorContainer(form);

    // Clear previous errors
    errorContainer.innerHTML = '';

    // Display new errors
    Object.keys(errors).forEach(field => {
        errors[field].forEach(message => {
            const errorElement = document.createElement('p');
            errorElement.classList.add('error-message');
            errorElement.textContent = message;
            errorContainer.appendChild(errorElement);
        });
    });
}

function createErrorContainer(form) {
    const errorContainer = document.createElement('div');
    errorContainer.classList.add('error-messages');
    form.prepend(errorContainer);  // Place the error container at the top of the form
    return errorContainer;
}

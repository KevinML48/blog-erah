<div id="reply-form-{{ $parentId }}" class="mt-2">
    <form action="{{ route('comments.store', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Comment Body--}}
        @error('body')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
        <textarea name="body" rows="2" class="w-full border rounded-md p-2"
                  placeholder="Votre commentaire..." maxlength="255" id="commentBody-{{ $parentId }}"></textarea>

        <div class="flex justify-between items-center mt-2">

            <div class="flex justify-between items-center">
                <!-- Search Button -->
                <x-secondary-button onclick="toggleModal({{ $parentId }})"> GIF </x-secondary-button>

                <!-- Image Upload -->
                <div id="mediaUpload-{{ $parentId }}" class="media-upload ml-2">
                    <input type="file" name="media" id="media-{{ $parentId }}"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-white" accept="image/*">
                    @error('media')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex items-center">
                <div class="counter mr-2">
                    <span id="current-{{ $parentId }}">0</span>
                    <span id="maximum">/ 255</span>
                </div>
                <x-primary-button>{{ __('Poster') }}</x-primary-button>
            </div>

        </div>

        <!-- Display selected GIF below the search and poster -->
        <div id="selectedGifContainer-{{ $parentId }}" class="mt-2 hidden">
            <img id="selectedGif-{{ $parentId }}" src="" alt="Selected GIF" class="w-32 h-32 rounded-md">
            <button type="button" id="cancelButton-{{ $parentId }}"
                    class="bg-red-500 text-white px-2 py-1 rounded ml-2 hidden" onclick="unselectGIF({{ $parentId }})">
                Cancel
            </button>
        </div>

        <input type="hidden" name="parent_id" value="{{ $parentId ?? '' }}">
        <input type="hidden" name="gif_url" id="gifUrl-{{ $parentId }}" value=""> <!-- Hidden input for GIF URL -->
    </form>
</div>


<!-- Modal Structure -->
<div id="searchModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden items-center justify-center">
    <div class="rounded-lg p-6 w-96  erah-box">
        <h2 class="text-lg font-bold mb-4">Search for Media</h2>
        <input type="text" id="searchQuery" class="w-full border rounded-md p-2 mb-4" placeholder="Search...">

        <div id="gifResults" class="flex flex-wrap gap-2 mb-4"></div> <!-- GIF results will appear here -->

        <div class="flex justify-end">
            <x-cancel-button onclick="toggleModal()"> Annuler </x-cancel-button>
            <x-secondary-button onclick="performSearch()"> Chercher </x-secondary-button>
        </div>
    </div>
</div>




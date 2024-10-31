<div id="reply-form-{{ $parentId }}" class="mt-2">
    <form id="commentForm-{{ $parentId }}" action="{{ route('comments.store', $post->id) }}" method="POST"
          enctype="multipart/form-data"
{{--          onsubmit="return handleSubmit(event, '{{ $parentId }}')"--}}
    >
        @csrf
        {{-- Comment Body --}}
        @error('input-body-' . $parentId)
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
        <div>
            <div
                id="commentBody-{{ $parentId }}"
                contenteditable="true"
                class="w-full border rounded-md p-2 bg-white text-black comment-body"
                data-parent-id="{{ $parentId }}"
            ></div>
            <input type="hidden" name="input-body-{{ $parentId }}" id="commentInput-{{ $parentId }}" value="{{ old('input-body-' . $parentId) }}"/>
        </div>


        <div class="flex justify-between items-center mt-2">
            <div class="flex justify-between items-center">
                <!-- Search Button -->
                <x-secondary-button onclick="toggleModal({{ $parentId }})"> GIF</x-secondary-button>

                <!-- Image Upload -->
                <div id="mediaUpload-{{ $parentId }}" class="media-upload ml-2">
                    <input type="file" name="media" id="media-{{ $parentId }}"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-white" accept="image/*"
                           onchange="previewImage({{ $parentId }})">
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

        <!-- Display media zone -->
        <div id="displayMediaZone-{{ $parentId }}" class="mt-2 hidden">
            <img id="selectedImage-{{ $parentId }}" src="" alt="Selected Image" class="w-32 h-32 rounded-md hidden">
            <img id="selectedGif-{{ $parentId }}" src="" alt="Selected GIF" class="w-32 h-32 rounded-md hidden">
            <x-cancel-button id="cancelButton-{{ $parentId }}" onclick="clearMedia({{ $parentId }})"> Annuler
            </x-cancel-button>
        </div>

        <input type="hidden" name="parent_id" value="{{ $parentId ?? '' }}">
        <input type="hidden" name="gif_url" id="gifUrl-{{ $parentId }}" value="">
    </form>
</div>

<!-- Modal Structure -->
<div id="searchModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden items-center justify-center">
    <div class="rounded-lg p-6 max-w-[66%] max-h-[66%] overflow-auto flex flex-col erah-box">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Recherche de GIF</h2>
            <a href="https://tenor.com/legal-terms" target="_blank" class="text-xs text-gray-400 hover:underline">Powered
                by Tenor</a>
        </div>
        <x-text-input id="searchQuery" placeholder="Search Tenor"></x-text-input>
        <div id="gifResults" class="grid grid-cols-2 gap-2 mb-4 overflow-auto flex-grow"></div>
        <div class="flex justify-end">
            <x-cancel-button onclick="toggleModal()"> Annuler</x-cancel-button>
            <x-secondary-button onclick="performSearch()"> Chercher</x-secondary-button>
        </div>
    </div>
</div>

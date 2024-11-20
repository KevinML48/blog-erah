<template id="reply-form-template" style="display: none;">
    <form id="commentForm" action="{{ route('comments.store') }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        @if(request()->route('post'))
            <input type="hidden" name="post_id" value="{{ request()->route('post')->id }}">
        @endif
        {{-- Comment Body --}}
        @error('input-body')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
        <div>
            <textarea id="commentInput"
                      name="input-body"
                      class="w-full border rounded-md p-2 bg-white text-black caret-red-600 focus:outline-none focus:ring focus:ring-red-500"
                      rows="5"
                      data-parent-id=""
                      placeholder="Écrivez votre commentaire ici..."
                      x-data="{
                  files: [],
                  handlePaste(event) {
                      const clipboardData = event.clipboardData || window.clipboardData;
                      if (!clipboardData) return;

                      const items = clipboardData.items;
                      for (let item of items) {
                          if (item.kind === 'file' && item.type.startsWith('image/')) {
                              const file = item.getAsFile();
                              if (file) {
                                  this.files.push(file);
                                  this.updateFileInput();
                              }
                          }
                      }
                  },
                  updateFileInput() {
                      const parentId = this.$el.dataset.parentId;
                      const fileInput = document.getElementById(`media-${parentId}`);
                      if (fileInput) {
                          const dataTransfer = new DataTransfer();
                          this.files.forEach(file => dataTransfer.items.add(file));
                          fileInput.files = dataTransfer.files;

                          previewImage(parentId);
                      }
                  }
              }"
                      @paste="handlePaste($event)"></textarea>
            <div class="flex justify-between items-center mt-2">
                <div class="flex justify-between items-center">
                    <!-- GIF Button -->
                    <button
                        class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition ease-in-out duration-150"
                        id="gifButton">GIF
                    </button>


                    <!-- Image Upload -->
                    <div id="mediaUpload" class="media-upload ml-2">
                        <input type="file" name="media" id="media" class="hidden" accept="image/*">
                        <label for="media" id="media-label" class="erah-button"> Télécharger une Image </label>
                        @error('media')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="counter flex-none m-2">
                        <span id="current">0</span>
                        <span id="maximum">/ 255</span>
                    </div>
                    <x-primary-button>{{ __('Poster') }}</x-primary-button>
                </div>
            </div>

            <!-- Display media zone -->
            <div id="displayMediaZone" class="mt-2 hidden">
                <img id="selectedImage" src="" alt="Selected Image" class="w-32 h-32 rounded-md hidden">
                <img id="selectedGif" src="" alt="Selected GIF" class="w-32 h-32 rounded-md hidden">
                <x-cancel-button id="cancelButton" onclick="clearMedia()"> Annuler</x-cancel-button>
            </div>

            <input type="hidden" name="parent_id" value="">
            <input type="hidden" name="gif_url" id="gifUrl" value="">
    </form>
</template>

<div id="searchModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden items-center justify-center">
    <div class="rounded-lg p-6 max-w-[66%] max-h-[66%] overflow-auto flex flex-col erah-box">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Recherche de GIF</h2>
            <a href="https://tenor.com/legal-terms" target="_blank" class="text-xs text-gray-400 hover:underline">Powered
                by Tenor</a>
        </div>
        <form onsubmit="performSearch(); return false;" class="flex flex-col">
            <x-text-input id="searchQuery" placeholder="Search Tenor" class="w-full mb-2"></x-text-input>
            <div id="gifResults" class="grid grid-cols-2 gap-2 mb-4 overflow-auto flex-grow"></div>
            <div class="flex justify-end">
                <x-cancel-button onclick="toggleModal()"> Annuler</x-cancel-button>
                <x-secondary-button type="submit"> Chercher</x-secondary-button> <!-- Submit button -->
            </div>
        </form>
    </div>
</div>


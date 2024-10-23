<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700">Titre</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('title')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div>
                            <label for="body" class="block font-medium text-sm text-gray-700">Contenu</label>
                            <textarea name="body" id="body" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('body') }}</textarea>
                            @error('body')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Publication Time -->
                        <div>
                            <label for="publication_time" class="block font-medium text-sm text-gray-700">Date de publication</label>
                            <input type="datetime-local" name="publication_time" id="publication_time" value="{{ old('publication_time') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('publication_time')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Media -->
                        <div>
                            <label for="media_type" class="block font-medium text-sm text-gray-700">Type de Média</label>
                            <select name="media_type" id="media_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="image" {{ old('media_type') == 'image' ? 'selected' : '' }}>Uploader une image</option>
                                <option value="video" {{ old('media_type') == 'video' ? 'selected' : '' }}>Lien vidéo</option>
                            </select>
                        </div>

                        <div id="mediaUpload" class="media-upload">
                            <label for="media" class="block font-medium text-sm text-gray-700">Image</label>
                            <input type="file" name="media" id="media" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept="image/*">
                            @error('media')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="mediaLink" class="media-link hidden">
                            <label for="video_link" class="block font-medium text-sm text-gray-700">Vidéo</label>
                            <input type="url" name="video_link" id="video_link" value="{{ old('video_link') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="https://example.com/video">
                            @error('video_link')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="mt-4">
                            <button type="submit" class="bg-white hover:bg-gray-100 text-sm text-gray-700 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                Créer Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mediaTypeSelect = document.getElementById('media_type');
            const mediaUpload = document.getElementById('mediaUpload');
            const mediaLink = document.getElementById('mediaLink');

            mediaTypeSelect.addEventListener('change', function () {
                if (this.value === 'image') {
                    mediaUpload.classList.remove('hidden');
                    mediaLink.classList.add('hidden');
                } else {
                    mediaUpload.classList.add('hidden');
                    mediaLink.classList.remove('hidden');
                }
            });

            mediaTypeSelect.dispatchEvent(new Event('change'));
        });
    </script>
</x-app-layout>

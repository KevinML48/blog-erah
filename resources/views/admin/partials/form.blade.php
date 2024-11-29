@csrf

<div class="grid grid-cols-1 gap-6">
    <!-- Title -->
    <div>
        <x-input-label for="title" :value="__('Titre')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post->title ?? '')" required autofocus autocomplete="title" />
        @error('title')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Body -->
    <div>
        <x-input-label for="body" :value="__('Contenu')" />
        <x-text-area id="body" name="body" rows="5" class="mt-1 block w-full" required autofocus autocomplete="body" :value="old('body', $post->body ?? '')"></x-text-area>
        @error('body')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Publication Time -->
    <div>
        <input type="hidden" name="timezone" id="timezone">
        <x-input-label for="publication_time" :value="__('Date de publication')" />
        <input type="datetime-local" name="publication_time" id="publication_time"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @error('publication_time')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Media Type -->
    <div>
        <x-input-label for="media_type" :value="__('Type de Média')" />
        <select name="media_type" id="media_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="image" {{ old('media_type', $post->media_type ?? '') == 'image' ? 'selected' : '' }}>Uploader une image</option>
            <option value="video" {{ old('media_type', $post->media_type ?? '') == 'video' ? 'selected' : '' }}>Lien vidéo</option>
        </select>
    </div>

    <!-- Media Upload -->
    <div id="mediaUpload" class="media-upload">
        <x-input-label for="media" :value="__('Image')" />
        <input type="file" name="media" id="media" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-white" accept="image/*">
        @error('media')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Video Link -->
    <div id="mediaLink" class="media-link hidden">
        <x-input-label for="video_link" :value="__('Vidéo')" />
        <input type="url" name="video_link" id="video_link" value="{{ old('video_link', $post->video_link ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="https://example.com/video">
        @error('video_link')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Theme Selection -->
    <div>
        <x-input-label for="theme_id" :value="__('Thème')" />
        <select name="theme_id" id="theme_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">-- Choisir un Thème --</option>
            @foreach ($themes as $theme)
                <option value="{{ $theme->id }}" {{ old('theme_id', $post->theme_id ?? '') == $theme->id ? 'selected' : '' }}>
                    {{ $theme->name }}
                </option>
            @endforeach
        </select>
        @error('theme_id')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Submit -->
    <div class="mt-4">
        <x-button.primary>
            {{ isset($post) ? __('Modifier Post') : __('Créer Post') }}
        </x-button.primary>
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
<script>
    document.getElementById('timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone;
</script>

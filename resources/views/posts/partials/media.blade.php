@if($post->media)
    @if (str_contains($post->media, 'youtube.com') || str_contains($post->media, 'youtu.be'))
        @php
            preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|(?:youtu\.be\/))([^&\n]{11})/', $post->media, $matches);
            $videoId = $matches[1] ?? '';
        @endphp
        @if ($videoId)
            <div class="relative" style="padding-top: 56.25%; width: 100%;"> <!-- 16:9 aspect ratio -->
                <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen
                        class="absolute top-0 left-0 w-full h-full"></iframe>
            </div>
        @else
            <!-- Lien non valide -->
            <img src="{{ asset('storage/images/placeholder.png') }}" alt="placeholder"
                 class="object-cover h-full w-full">
        @endif
    @else
        <img src="{{ asset('storage/' . $post->media) }}" alt="{{ $post->title }}"
             class="object-cover h-full w-full">
    @endif
@else
    <img src="{{ asset('storage/images/placeholder.png') }}" alt="placeholder" class="object-cover h-full w-full">
@endif

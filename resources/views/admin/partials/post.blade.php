<div class="flex border-b pb-4 mb-4">

    <!-- Media Display -->
    <div class="w-1/3 pr-4">
        <div class="w-48 bg-gray-200 flex items-center justify-center overflow-hidden">
            @if($post->media)
                @if (str_contains($post->media, 'youtube.com') || str_contains($post->media, 'youtu.be'))
                    @php
                        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|(?:youtu\.be\/))([^&\n]{11})/', $post->media, $matches);
                        $videoId = $matches[1] ?? '';
                    @endphp
                    @if ($videoId)
                        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen class="w-full h-full"></iframe>
                    @else
                        <!-- Lien non valide -->
                        <img src="{{ asset('storage/images/placeholder.png') }}" alt="placeholder" class="object-cover h-full w-full">
                    @endif
                @else
                    <img src="{{ asset('storage/' . $post->media) }}" alt="{{ $post->title }}" class="object-cover h-full w-full">
                @endif
            @else
                <img src="{{ asset('storage/images/placeholder.png') }}" alt="placeholder" class="object-cover h-full w-full">
            @endif
        </div>
    </div>

    <!-- Post -->
    <div class="w-2/3 pl-4">
        <h4 class="font-semibold text-lg">{{ $post->title }}</h4>
        <p class="text-gray-600">Par
            <a href="{{ route('profile.show', ['username' => $post->user->name]) }}" class="text-blue-600 hover:underline">
                {{ $post->user->name }}
            </a>
            le {{ $post->created_at->format('d-m-Y H:i') }}</p>

        <p class="mt-2">{{ Str::limit($post->body, 100) }}</p>
    </div>

</div>

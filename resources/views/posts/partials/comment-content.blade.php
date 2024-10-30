<div class="border-b border-gray-300 bg-gray-900 py-2 p-2 flex flex-col">
    <div class="flex items-start space-x-2">
        <!-- Profile picture -->
        @if($content->user->profile_picture)
            <div>
                <img src="{{ asset('storage/' . $content->user->profile_picture) }}" alt="Profile Picture"
                     class="w-12 h-12 rounded-full object-cover">
            </div>
        @else
            <div>
                <img src="{{ asset('storage/profile_picture/default.png')}}" alt="Profile Picture"
                     class="w-12 h-12 rounded-full object-cover">
            </div>
        @endif

        <div class="flex-1">
            <div class="flex justify-between items-center">
                <!-- Name -->
                <a href="{{ route('profile.show', ['username' => $content->user->name]) }}" class="erah-link font-bold">
                    {{ $content->user->name }}
                </a>

                <div>
                    <!-- Delete Link -->
                    @if (auth()->user() && (auth()->user()->id === $content->user->id || auth()->user()->isAdmin()))
                        <form action="{{ route('comments.destroy', $content->id) }}" method="POST" class="inline"
                              onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">
                                Supprimer
                            </button>
                        </form>
                    @endif

                    <!-- Creation date and link -->
                    <a href="{{ route('comments.show', ['post' => $content->comment->post->id, 'comment' => $content->id]) }}"
                       class="hover:underline">
                        <span class="text-gray-500 text-sm convert-time"
                              data-time="{{ $content->created_at->toIso8601String() }}"></span>
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div class="py-2">
                {!! nl2br(e($content->body)) !!}
            </div>
            @if ($content->media)
                <div class="py-2">
                    @if (filter_var($content->media, FILTER_VALIDATE_URL) && strpos($content->media, 'tenor.com') !== false)
                        <!-- If it's a Tenor GIF URL -->
                        <img src="{{ $content->media }}" alt="Comment GIF" class="object-contain h-48 w-48">
                    @else
                        <!-- If it's an uploaded image -->
                        <img src="{{ asset('storage/' . $content->media) }}" alt="Comment Image" class="object-contain h-48 w-48">
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');
    }
</script>

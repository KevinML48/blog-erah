<div class="border-b border-gray-300 bg-gray-900 py-2 p-2 flex flex-col">
    <div class="flex items-start space-x-2">
        @if($comment->user->profile_picture)
            <div>
                <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" alt="Profile Picture"
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
                <a href="{{ route('profile.show', ['username' => $comment->user->name]) }}" class="erah-link font-bold">
                    {{ $comment->user->name }}
                </a>

                <a href="{{ route('comments.show', ['post' => $comment->comment->post->id, 'comment' => $comment->id]) }}" class="hover:underline">
                    <span class="text-gray-500 text-sm convert-time" data-time="{{ $comment->created_at }}"></span>
                </a>
            </div>

            <div class="mt-1">
                {!! nl2br(e($comment->body)) !!}
            </div>
        </div>
    </div>
</div>


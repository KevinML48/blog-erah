<div class="flex items-center py-2 ml-2">
    <!-- Reply -->
    <div class="flex items-center">
        @auth
            <button onclick="toggleReplyForm({{ $comment->id }})" class="cursor-pointer">
                <x-svg-speech-bubble/>
            </button>
        @else
            <a href="{{ route('login') }}" class="cursor-pointer">
                <x-svg-speech-bubble/>
            </a>
        @endauth
        @if ($comment->replies->count() > 0)
            <span class="text-gray-300 ml-1">({{ $comment->replies->count() }})</span>
        @endif
    </div>

    <!-- Like -->
    <div class="flex items-center ml-2">
        @auth
            <div class="flex items-center bg-transparent border-0 cursor-pointer">
                <!-- Unliked Button -->
                <button onclick="likeComment({{ $comment->id }})" id="like-comment-button-{{ $comment->id }}"
                        class="flex items-center {{ $comment->content->likes()->where('user_id', auth()->id())->exists() ? 'hidden' : '' }}">
                    <x-svg-heart id="unfilled-icon-{{ $comment->id }}" :filled="false"/>
                </button>

                <!-- Liked Button -->
                <button onclick="unlikeComment({{ $comment->id }})" id="unlike-comment-button-{{ $comment->id }}"
                        class="flex items-center text-red-600 {{ $comment->content->likes()->where('user_id', auth()->id())->exists() ? '' : 'hidden' }}">
                    <x-svg-heart id="filled-icon-{{ $comment->id }}" :filled="true"/>
                </button>
            </div>
        @else
            <!-- Display filled heart with link to login -->
            <a href="{{ route('login') }}" class="flex items-center text-red-600">
                <x-svg-heart id="filled-icon-{{ $comment->id }}" :filled="true"/>
                @if ($comment->content->likes()->count() > 0)
                    <span id="likes-comment-count-{{ $comment->id }}" class="ml-1">
                        ({{ $comment->content->likes()->count() }})
                    </span>
                @endif
            </a>
        @endauth
    </div>
</div>

<div id="form-container-{{ $comment->id }}"></div> <!-- Empty container where the form will be appended -->

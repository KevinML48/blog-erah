<div class="flex items-center py-2 ml-2">
    <!-- Reply -->
    <div class="flex items-center">
        <button onclick="toggleReplyForm({{ $comment->id }})" class="cursor-pointer">
            <x-svg-speech-bubble/>
        </button>
        @if ($comment->replies->count() > 0)
            <span class="text-gray-300 ml-1">({{ $comment->replies->count() }})</span>
        @endif
    </div>

    <!-- Like -->
    <div class="flex items-center ml-2">
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

            <!-- Likes Count -->
            @if ($comment->content->likes()->count() > 0)
                <span
                    id="likes-comment-count-{{ $comment->id }}">({{ $comment->content->likes()->count() }})</span>
            @else
                <span
                    id="likes-comment-count-{{ $comment->id }}"></span>
            @endif
        </div>
    </div>

</div>

<div id="form-container-{{ $comment->id }}"></div> <!-- Empty container where the form will be appended -->

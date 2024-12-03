<div class="flex items-center py-2 ml-2">
    <!-- Reply -->
    <div class="flex items-center">
        @auth
            <button onclick="toggleReplyForm({{ $comment->id }})" class="cursor-pointer">
                <x-svg.speech-bubble/>
            </button>
        @else
            <a href="{{ route('comments.show.redirect', [$comment->post_id, $comment]) }}" class="cursor-pointer">
                <x-svg.speech-bubble/>
            </a>
        @endauth
        @if ($comment->replies_count > 0)
            <span class="text-gray-300 ml-1">({{ $comment->replies_count }})</span>
        @endif
    </div>

    <!-- Like -->
    <div class="flex items-center ml-2">
        @auth
            <div class="flex items-center bg-transparent border-0 cursor-pointer">
                <!-- Unliked Button -->
                <button onclick="likeComment({{ $comment->id }})" id="like-comment-button-{{ $comment->id }}"
                        class="flex items-center {{ $comment->content->is_liked_by_auth_user ? 'hidden' : '' }}">
                    <x-svg.heart id="unfilled-icon-{{ $comment->id }}" :filled="false"/>
                </button>
                <!-- Liked Button -->
                <button onclick="unlikeComment({{ $comment->id }})" id="unlike-comment-button-{{ $comment->id }}"
                        class="flex items-center text-red-600 {{ $comment->content->is_liked_by_auth_user ? '' : 'hidden' }}">
                    <x-svg.heart id="filled-icon-{{ $comment->id }}" :filled="true"/>
                </button>
            </div>
        @else
            <!-- Display filled heart with link to login -->
            <a href="{{ route('comments.show.redirect.like', [$comment->post_id, $comment->id]) }}"
               class="flex items-center text-red-600">
                <x-svg.heart id="filled-icon-{{ $comment->id }}" :filled="true"/>
            </a>
        @endauth
        @if ($comment->content->likes_count > 0)
            <span id="likes-comment-count-{{ $comment->id }}" class="ml-1">
                ({{ $comment->content->likes_count }})
            </span>
        @endif
    </div>
</div>

<div id="form-container-{{ $comment->id }}"></div> <!-- Empty container where the form will be appended -->

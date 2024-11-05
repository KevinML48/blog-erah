<div class="border-l border-gray-300 py-2">
    <div class="flex items-start">
        <div class="border-b border-gray-300 py-2 flex-shrink-0 w-12 h-10"></div>
        <div class="py-2 flex-grow">
            @if ($comment->contentExists())
                @include('posts.partials.comment-content', ['content' => $comment->content])
            @else
                <div class="py-5 p-2">
                    <span class="text-red-500 italic">Commentaire introuvable.</span>
                </div>
            @endif

            <!-- React section -->
            @if ($comment->contentExists())
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

                <div id="reply-form-{{ $comment->id }}" class="hidden mt-2">
                    @include('posts.partials.comment-form', ['parentId' => $comment->id, 'post' => $comment->post])
                </div>
            @endif

            <!-- Display Replies -->
            <div id="replies-container-{{ $comment->id }}" class="ml-6 mt-2">
                @if ($depth < 2)
                    @foreach ($comment->replies()->take(2)->get() as $reply)
                        @include('posts.partials.comment', ['comment' => $reply, 'depth' => $depth + 1])
                    @endforeach
                @endif
            </div>

            @if ($depth > 1)
                @if ($comment->replies()->count() > 0)
                    <button class="load-more-replies text-sm text-blue-600"
                            data-url="{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}"
                            data-page="0"
                            onclick="loadMore(this, '{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}')">
                        Charger plus de commentaires
                    </button>
                @endif
            @else
                @if ($comment->replies()->count() > 2)
                    <button class="load-more-replies text-sm text-blue-600"
                            data-url="{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}"
                            data-page="1"
                            onclick="loadMore(this, '{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}')">
                        Charger plus de commentaires
                    </button>
                @endif
            @endif
        </div>
    </div>
</div>

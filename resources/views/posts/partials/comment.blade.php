<div class="border-l border-gray-300 py-2">
    <div class="flex items-start"> <!-- Added flex container -->
        <div class="border-b border-gray-300 py-2 flex-shrink-0 w-12 h-10"></div>
        <div class="py-2 flex-grow">
            @if ($comment->contentExists())
                @include('posts.partials.comment-content', ['content' => $comment->content])
            @else
                <span class="text-red-500 italic">Commentaire introuvable.</span>
            @endif

            <!-- Replies Button -->
            @if ($comment->contentExists())
                <button class="text-sm text-blue-600" onclick="showReplyForm({{ $comment->id }})">Répondre</button>

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

<script>
    function showReplyForm(commentId) {
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        replyForm.classList.toggle('hidden');
    }
</script>

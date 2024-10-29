<div class="border-b border-gray-300 py-2">
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
    <div id="replies-container-{{ $comment->id }}" class="ml-4 mt-2">
        @foreach ($comment->replies()->take(2)->get() as $reply)
        @include('posts.partials.comment', ['comment' => $reply])
        @endforeach
    </div>

    @if ($comment->replies()->count() > 2)
    <button class="load-more-replies text-sm text-blue-600" data-url="{{ route('comments.loadMoreReplies', ['comment' => $comment->id]) }}" data-page="1">
        Charger plus de commentaires
    </button>
    @endif
</div>

<script>
    function showReplyForm(commentId) {
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        replyForm.classList.toggle('hidden');
    }
</script>


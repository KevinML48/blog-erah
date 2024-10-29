<div class="border-b border-gray-300 py-2">
    @if ($comment->contentExists())
        @include('posts.partials.comment-content', ['comment' => $comment->content])
    @else
        <span class="text-red-500 italic">Commentaire introuvable.</span>
    @endif

    <!-- Replies Button -->
    @if ($comment->contentExists())
        <button class="text-sm text-blue-600" onclick="showReplyForm({{ $comment->id }})">Répondre</button>

        <div id="reply-form-{{ $comment->id }}" class="hidden mt-2">
            @include('posts.partials.comment-form', ['parentId' => $comment->id, 'post' => $comment->post,])
        </div>
    @endif

    <!-- Display Replies -->
    @if($comment->replies->isNotEmpty())
        <div class="ml-4 mt-2">
            @foreach ($comment->replies as $reply)
                @include('posts.partials.comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>

<script>
    function showReplyForm(commentId) {
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        replyForm.classList.toggle('hidden');
    }
</script>

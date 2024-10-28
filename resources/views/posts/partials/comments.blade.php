<div class="mt-6">
    <h4 class="font-semibold text-lg">Commentaires ({{ $totalCommentsCount }})</h4>

    <!-- Comment Form -->
    @include('posts.partials.comment-form', ['parentId' => '', 'post' => $post,])

    <!-- Displaying Comments -->
    <div class="mt-4">
        @foreach ($comments as $comment)
            @include('posts.partials.comment', ['comment' => $comment])
        @endforeach
    </div>
</div>

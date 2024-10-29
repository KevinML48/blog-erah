<div class="mt-6">
    @if (request()->routeIs('posts.show'))
        <h4 class="font-semibold text-lg">Commentaires ({{ $totalCommentsCount }})</h4>

        <!-- Comment Form -->
        <div class="mb-4">
            @include('posts.partials.comment-form', ['parentId' => '', 'post' => $post,])
        </div>
    @endif

    <!-- Displaying Comments -->
    @if (request()->routeIs('comments.show'))
        <div class="mb-4">
            <!-- Link back to the full post view -->
            <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600">
                ← Retour à la discussion complète
            </a>

            <!-- Link to the parent comment, if one exists -->
            @if ($comment->parent_id)
                <a href="{{ route('comments.show', ['post' => $post->id, 'comment' => $comment->parent_id]) }}" class="text-blue-600 ml-4">
                    ← Retour au commentaire parent
                </a>
            @endif
        </div>
    @endif
        <div class="mt-4" id="comments-container">
            @foreach ($comments as $comment)
                @include('posts.partials.comment', ['comment' => $comment])
            @endforeach
        </div>

        @if ($comments->hasMorePages())
            <button id="load-more" data-url="{{ route('comments.loadMore', ['post' => $post->id]) }}" data-page="{{ $comments->currentPage() }}">
                Load More
            </button>
        @endif
</div>

<script>
    document.getElementById('load-more').addEventListener('click', function () {
        const button = this;
        const url = button.getAttribute('data-url');
        const currentPage = parseInt(button.getAttribute('data-page'));

        fetch(`${url}?page=${currentPage}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('comments-container').insertAdjacentHTML('beforeend', data.comments);

                button.setAttribute('data-page', currentPage + 1);

                if (!data.hasMore) {
                    button.style.display = 'none';
                }
            });
    });
</script>


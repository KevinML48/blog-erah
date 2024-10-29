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
                <a href="{{ route('comments.show', ['post' => $post->id, 'comment' => $comment->parent_id]) }}"
                   class="text-blue-600 ml-4">
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
        <button id="load-more" data-url="{{ route('comments.loadMore', ['post' => $post->id]) }}"
                data-page="{{ $comments->currentPage() }}">
            Charger plus de commentaires
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

                convertTimes();

                button.setAttribute('data-page', currentPage + 1);

                if (!data.hasMore) {
                    button.style.display = 'none';
                }
            });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.load-more-replies').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                const currentReplyPage = parseInt(this.getAttribute('data-page'));

                fetch(`${url}?page=${currentReplyPage}`)
                    .then(response => response.json())
                    .then(data => {
                        const repliesContainer = document.getElementById(`replies-container-${data.commentId}`);

                        if (repliesContainer) {
                            repliesContainer.insertAdjacentHTML('beforeend', data.replies);

                            convertTimes();

                            this.setAttribute('data-page', currentReplyPage + 1);

                            if (!data.hasMore) {
                                this.style.display = 'none';
                            }
                        } else {
                            console.error(`Replies container with ID 'replies-container-${data.commentId}' not found.`);
                        }
                    })
                    .catch(error => console.error('Error loading more replies:', error));
            });
        });
    });
</script>



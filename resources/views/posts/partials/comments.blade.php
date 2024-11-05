<div class="mt-6 comment-section">
    @if (request()->routeIs('posts.show'))
        <h4 class="font-semibold text-lg">Commentaires ({{ $totalCommentsCount }})</h4>

        <!-- Comment Form -->
        <div id="reply-form--1}" class="mb-4">
            @include('posts.partials.comment-form', ['parentId' => -1, 'post' => $post,])
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
            @include('posts.partials.comment', ['comment' => $comment, 'depth' => 0])
        @endforeach
    </div>


    @if ($comments->hasMorePages())
        <button id="load-more" data-url="{{ route('comments.loadMore', ['post' => $post->id]) }}"
                data-page="{{ $comments->currentPage() }}"
                onclick="loadMore(this, '{{ route('comments.loadMore', ['post' => $post->id]) }}')">
            Charger plus de commentaires
        </button>
    @endif
</div>

<script src="{{ asset('js/comment-form.js') }}" defer></script>
<script src="{{ asset('js/load-more.js') }}" defer></script>


@if (request()->routeIs('comments.show'))
    <script>
        window.onload = function () {
            const targetElement = document.querySelector('.comment-section');
            if (targetElement) {
                targetElement.scrollIntoView({behavior: 'smooth'});
            }
        };
    </script>
@endif

@if (session()->has('failed_id'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const failedId = "{{ session('failed_id') }}";
            const targetElement = document.getElementById("reply-form-" + failedId);
            if(failedId > 0) {
                showReplyForm(failedId);
            }
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        });
    </script>
@endif


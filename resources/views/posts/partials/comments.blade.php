<div class="mt-6 comment-section" id="comment-section">
    @if(session('fragment'))
        <script>
            window.onload = function () {
                // Add the fragment to the URL
                window.location.hash = "{{ session('fragment') }}";

                // Optionally scroll to the section directly
                var section = document.getElementById("{{ session('fragment') }}");
                if (section) {
                    section.scrollIntoView({behavior: "smooth"});
                }
            };
        </script>
    @endif
    @if (request()->routeIs('posts.show'))
        <h4 class="font-semibold text-lg">Commentaires ({{ $totalCommentsCount }})</h4>

        @auth
            <!-- Main Comment Form -->
            <div id="form-container--1"></div> <!-- Empty container where the form will be appended -->
        @else
            <a href="{{ route('posts.show.redirect.comments', $post) }}" class="erah-link-amnesic">
                Connectez-vous pour réagir
            </a>

        @endauth
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
    <div class="mt-4" id="replies-container--1">
        @if (request()->routeIs('comments.show'))
            <!-- Display the parent comment, if one exists -->
            @if ($comment->parent_id)
                @include('posts.partials.comment', ['comment' => $comment->parent])
            @endif
        @endif
        @foreach ($comments as $comment)
            @include('posts.partials.comment-structure', ['comment' => $comment, 'depth' => 0])
        @endforeach
    </div>
    <div id="loader" class="hidden">
        <x-spinner/>
    </div>


    @if ($comments->hasMorePages())
        <button class="hidden" id="load-more" data-url="{{ route('comments.loadMore', ['post' => $post->id]) }}"
                data-page="{{ $comments->currentPage() }}"
                onclick="loadMore(this, '{{ route('comments.loadMore', ['post' => $post->id]) }}')">
            Charger plus de commentaires
        </button>
    @endif
</div>

<script src="{{ asset('js/comment-form.js') }}" defer></script>
<script src="{{ asset('js/follow.js') }}" defer></script>
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



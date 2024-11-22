@include('posts.partials.post-full')

    @auth
        <div class="mb-2">
            <a href="{{ route('posts.show', $post->id) }}#comment-section">
                <h4 class="erah-link text-right">Commenter →</h4>
            </a>
        </div>
    @else
        <a href="{{ route('posts.show.redirect', $post) }}" class="erah-link-amnesic">
            <h4 class="erah-link text-right">Connectez-vous pour réagir →</h4>
        </a>
    @endauth
<script src="{{ asset('js/likes.js') }}" defer></script>

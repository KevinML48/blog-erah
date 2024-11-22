@include('posts.partials.post-full')

    @auth
        <div class="mb-2">
            <a href="{{ route('posts.show', $post->id) }}#comment-section">
                <h4 class="erah-link text-right">Commenter →</h4>
            </a>
        </div>
    @endauth
<script src="{{ asset('js/likes.js') }}" defer></script>

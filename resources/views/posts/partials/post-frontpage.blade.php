@include('posts.partials.post-full')

    @auth
        <div class="mb-2">
            <a href="{{ route('posts.show', $post->id) }}#comment-section">
                <h4 class="erah-link text-right">{!! __("posts.comment") !!}</h4>
            </a>
        </div>
    @else
        <a href="{{ route('posts.show.redirect.comments', $post) }}" class="erah-link-amnesic">
            <h4 class="erah-link text-right">{!! __("posts.connect_to_react") !!}</h4>
        </a>
    @endauth
<script src="{{ asset('js/likes.js') }}" defer></script>

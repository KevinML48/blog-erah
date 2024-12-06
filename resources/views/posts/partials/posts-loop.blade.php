@forelse($posts as $post)
    @include('posts.partials.post-short', ['post' => $post])
@empty
    @if (!empty($emptyMessageKey))
        <p>{{ __($emptyMessageKey) }}</p>
    @endif
@endforelse

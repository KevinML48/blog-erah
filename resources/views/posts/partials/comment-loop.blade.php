@forelse ($comments as $comment)
    @include('posts.partials.comment', ['comment' => $comment, 'depth' => $depth ?? 0])
@empty
    @if (!empty($emptyMessageKey))
        <p>{{ __($emptyMessageKey) }}</p>
    @endif
@endforelse

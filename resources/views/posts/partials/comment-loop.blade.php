@foreach ($comments as $comment)
    @include('posts.partials.comment', ['comment' => $comment, 'depth' => $depth ?? 0])
@endforeach


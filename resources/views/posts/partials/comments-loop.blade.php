@foreach ($comments as $comment)
    @include('posts.partials.comment', ['comment' => $comment, 'depth' => 0])
@endforeach


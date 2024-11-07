@foreach ($comments as $comment)
    @include('posts.partials.comment-structure', ['comment' => $comment, 'depth' => 0])
@endforeach

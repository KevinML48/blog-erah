@foreach ($comments as $comment)
    @include('posts.partials.comment', ['comment' => $comment])
@endforeach


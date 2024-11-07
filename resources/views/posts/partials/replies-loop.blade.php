@foreach ($replies as $reply)
    @include('posts.partials.comment-structure', ['comment' => $reply, 'depth' => 0])
@endforeach

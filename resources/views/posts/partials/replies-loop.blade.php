@foreach ($replies as $reply)
    @include('posts.partials.comment', ['comment' => $reply, 'depth' => 0])
@endforeach

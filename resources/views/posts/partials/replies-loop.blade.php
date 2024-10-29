@foreach ($replies as $reply)
    @include('posts.partials.comment', ['comment' => $reply])
@endforeach

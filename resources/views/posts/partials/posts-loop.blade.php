@foreach($posts as $post)
    @include('posts.partials.post-short', ['post' => $post])
@endforeach

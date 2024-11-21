@foreach($posts as $post)
    @include('posts.partials.post', ['post' => $post])
@endforeach

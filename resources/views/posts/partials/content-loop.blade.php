@foreach ($contents as $content)
    @include('posts.partials.comment', ['comment' => $content->comment, 'depth' => -1])
@endforeach


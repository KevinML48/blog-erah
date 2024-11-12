@foreach ($contents as $content)
    @include('posts.partials.comment', ['comment' => $content->comment])
@endforeach


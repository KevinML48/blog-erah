<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div id="posts-container">
            @foreach($posts as $post)
                @include('posts.partials.post-short', ['post' => $post])
            @endforeach
        </div>
        <div id="loader" class="hidden flex justify-center items-center space-x-2">
            <x-spinner/>
        </div>

    </div>
</div>

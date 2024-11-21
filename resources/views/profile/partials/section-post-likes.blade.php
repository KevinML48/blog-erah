<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <h2 class="font-bold text-lg">Commentaires</h2>

        <div id="posts-container">
            @foreach($posts as $post)
                @include('posts.partials.post', ['post' => $post])
            @endforeach
        </div>
        <div id="loader" class="hidden flex justify-center items-center space-x-2">
            <div class="w-8 h-8 border-4 border-t-4 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>
            <span class="text-gray-500">Charge...</span>
        </div>

    </div>
</div>

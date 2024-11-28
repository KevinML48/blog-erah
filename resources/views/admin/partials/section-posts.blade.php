<!-- Posts -->
<div class="erah-box">
    <h2 class="mt-6 text-lg font-semibold">Posts</h2>
    <div class="mt-4">
        <a href="{{ route('admin.posts.create') }}" class="py-2 px-4 erah-link">
            Créer Nouveau Post
        </a>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-semibold">10 Derniers Posts</h3>
        <div class="space-y-4 mt-4">
            @foreach($posts as $post)
                @include('posts.partials.post-short', ['post' => $post])
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-lg bg-black font-semibold">Posts non publiés</h3>
        <div class="space-y-4 mt-4">
            @foreach($unpublishedPosts as $post)
                @include('posts.partials.post-short', ['post' => $post])
            @endforeach
        </div>
    </div>
</div>

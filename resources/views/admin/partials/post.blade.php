<div class="flex border-b pb-4 mb-4">

    <!-- Media Display -->
    <div class="w-1/3 pr-4">
        <div class="w-48 bg-gray-200 flex items-center justify-center overflow-hidden">
            @include('posts.partials.media', ['post' => $post])
        </div>
    </div>

    <!-- Post -->
    <div class="w-2/3 pl-4">

        <!-- Title -->
        <a href="{{ route('posts.show', $post->id) }}">
            <h4 class="font-semibold text-lg hover:underline inline-block">{{ $post->title }}</h4>
        </a>

        <!-- Edit link -->
        <div class="mt-1">
            <a href="{{ route('admin.posts.edit', $post->id) }}" class="erah-link">
                Éditer
            </a>
        </div>

        <!-- Credit -->
        <p class="text-gray-600">
            @include('posts.partials.credit', ['post' => $post])
        </p>

        <!-- Truncated Body -->
        <p class="mt-2">{{ Str::limit($post->body, 100) }}</p>
    </div>

</div>

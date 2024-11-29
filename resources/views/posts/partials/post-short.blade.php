<div class="flex flex-col md:flex-row border-b pb-4 mb-4">

    <!-- Media Display -->
    <div class="md:w-1/3 pr-4">
        <div class="w-64 bg-gray-200 flex items-center justify-center overflow-hidden">
            @include('posts.partials.media', ['post' => $post])
        </div>
    </div>

    <!-- Post -->
    <div class="md:w-2/3 pl-4">

        <!-- Title -->
        <a href="{{ route('posts.show', $post->id) }}">
            <h4 class="font-semibold text-lg hover:underline inline-block">{{ $post->title }}</h4>
        </a>

        <!-- Edit link -->
        @if(auth()->user() && auth()->user()->isAdmin())
            <div class="mt-1">
                <a href="{{ route('admin.posts.edit', $post->id) }}" class="erah-link">
                    {!! __("posts.admin.edit") !!}
                </a>
                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block ml-2" onsubmit="return confirmPostDelete()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">{!! __("posts.admin.delete") !!}</button>
                </form>
            </div>
            <script>
                function confirmPostDelete() {
                    return confirm({!! __("posts.admin.confirm") !!});
                }
            </script>
        @endif

        <!-- Theme Display -->
        <div class="mt-1">
            {{ $post->theme->name }}
        </div>

        <!-- Credit -->
        <p class="text-gray-600">
            @include('posts.partials.credit', ['post' => $post])
        </p>

        <!-- Truncated Body -->
        <p class="mt-2">{{ Str::limit($post->body, 100) }}</p>
    </div>

</div>

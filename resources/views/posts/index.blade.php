<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-center space-x-8">
            @foreach ($themes as $theme)
                <a href="{{ route('posts.theme', $theme->id) }}" class="erah-link">
                    {{ $theme->name }}
                </a>
            @endforeach
        </div>
    </x-slot>

    <div class="py-12">
        <div class="space-y-4">
            <div class="mt-6">
                <div class="space-y-4 mt-4">
                    @foreach($posts as $post)
                        @include('posts.partials.post', ['post' => $post])
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="pagination">
        {{ $posts->links() }}
    </div>
</x-app-layout>

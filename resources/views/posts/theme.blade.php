<x-app-layout>
    <x-slot name="title">
        Blog - {{ $themes->firstWhere('id', request()->route('id'))->name ?? '' }}
    </x-slot>
    <x-slot name="header">
        <div class="flex justify-center space-x-8">
            @foreach ($themes as $theme)
                <x-theme-link :href="route('posts.theme', $theme->id)"
                              :active="request()->routeIs('posts.theme') && request()->route('id') == $theme->id">
                    {{ $theme->name }}
                </x-theme-link>
            @endforeach
        </div>
    </x-slot>

    <div class="py-12">
        <div class="space-y-4">
            <div class="mt-6">
                <div class="space-y-4 mt-4">
                    @foreach($posts as $post)
                        @include('posts.partials.post-short', ['post' => $post])
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

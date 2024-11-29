<x-app-layout>
    <x-slot name="title">
        {!! __('Blog') !!}
    </x-slot>
    <x-slot name="header">
        <div class="flex justify-center space-x-8">
            @foreach ($themes as $theme)
                <x-theme-link :href="route('posts.theme', $theme->slug)"
                              :active="request()->routeIs('posts.theme') && request()->route('slug') == $theme->slug">
                    {{ $theme->name }}
                </x-theme-link>
            @endforeach
            @if(request()->routeIs('posts.theme'))
                <x-theme-link :href="route('posts.index')" :active="false">
                    {!! __('navigation.back') !!}
                </x-theme-link>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="border-b">
                @if($posts->onFirstPage())
                    @include('posts.partials.post-frontpage', ['post' => $posts[0]])
                @endif
            </div>
            <div class="mt-6">
                <div class="space-y-4 mt-4">
                    <div class="flex justify-center w-full">
                        <div class="w-full sm:w-auto sm:mx-auto">
                            @if($posts->onFirstPage())
                                @include('posts.partials.posts-loop', ['posts' => $posts->slice(1)])
                            @else
                                @include('posts.partials.posts-loop', ['posts' => $posts])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Pagination Links -->
    <div class="pagination">
        {{ $posts->links() }}
    </div>
</x-app-layout>

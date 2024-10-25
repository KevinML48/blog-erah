<x-app-layout>
    <x-slot name="header">
        {{ $theme->name }}
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
</x-app-layout>

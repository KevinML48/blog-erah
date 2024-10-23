<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @include('posts.partials.media', ['post' => $post])

                <h3 class="font-bold text-lg">{{ $post->title }}</h3>

                <p class="text-gray-600">
                    @include('posts.partials.credit', ['post' => $post])
                </p>

                <div class="mt-4">
                    <p>{{ $post->body }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

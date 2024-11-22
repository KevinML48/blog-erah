<x-app-layout>
    <x-slot name="header">
        {{ $post->title }}
    </x-slot>
    <x-slot name="title">
        {{ $post->title }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                @include('posts.partials.post-full')

                @include('posts.partials.comments', ['comments' => $comments])
            </div>
        </div>
    </div>
    <!-- Hidden comment form layout -->
    @include('posts.partials.comment-form')
    <!-- Hidden GIF Modal -->
    @include('posts.partials.gif-modal')


    <script src="{{ asset('js/likes.js') }}" defer></script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        {{ $post->title }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                @include('posts.partials.media', ['post' => $post])

                <h3 class="font-bold text-lg">{{ $post->title }}</h3>

                @if(auth()->user() && auth()->user()->isAdmin())
                    <div class="mt-1">
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="erah-link">
                            Éditer
                        </a>
                    </div>
                @endif

                <p class="text-gray-600">
                    @include('posts.partials.credit', ['post' => $post])
                </p>

                <div class="mt-4">
                    {!! nl2br(e($post->body)) !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

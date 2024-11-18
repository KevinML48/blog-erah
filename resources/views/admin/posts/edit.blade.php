<x-app-layout>
    <x-slot name="title">
        {{ __('Modifier Post') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Modifier Post') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="erah-box">

                @include('posts.partials.media', ['post' => $post])

                <form method="POST" action="{{ route('admin.posts.update', $post->id) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @include('admin.partials.form', ['post' => $post])
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

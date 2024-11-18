<x-app-layout>
    <x-slot name="header">
        Likes de {{ $user->name }}
    </x-slot>
    <x-slot name="header">
        Likes de {{ $user->name }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <h2 class="font-bold text-lg">Commentaires</h2>

            <div id="comments-container">
                @foreach ($contents as $content)
                    @include('posts.partials.comment', ['comment' => $content->comment])
                @endforeach
            </div>

            <!-- Pagination Links -->
            {{ $contents->links() }}

        </div>
    </div>

    <script src="{{ asset('js/comment-form.js') }}" defer></script>
    <script src="{{ asset('js/follow.js') }}" defer></script>
    <script src="{{ asset('js/likes.js') }}" defer></script>
</x-app-layout>

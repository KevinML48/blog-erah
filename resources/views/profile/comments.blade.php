<x-app-layout>
    <x-slot name="header">
        Commentaires de {{ $user->name }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <h2 class="font-bold text-lg">Commentaires</h2>

            <div id="comments-container">
                @foreach ($comments as $comment)
                    @include('posts.partials.comment-content', ['content' => $comment])
                @endforeach
            </div>

            <!-- Pagination Links -->
            {{ $comments->links() }}

        </div>
    </div>
</x-app-layout>

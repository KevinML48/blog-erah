<x-app-layout>
    <x-slot name="header">
        {{ $user->name }}
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if($user->profile_picture)
                <div class="erah-box">
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture"
                         class="w-24 h-24 rounded-full object-fill">
                </div>
            @endif

            <div class="erah-box">
                <div class="p-6">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>S'est inscrit le :</strong> {{ $user->created_at->format('d M, Y') }}</p>
                </div>
            </div>

                <!-- Section for Latest Comments -->
                <div class="erah-box">
                    <div class="p-6">
                        <h2 class="font-bold text-lg">Derniers commentaires</h2>
                        <div class="space-y-4">
                            @foreach ($comments as $comment)
                                @include('posts.partials.comment-content', ['content' => $comment])
                            @endforeach

                            @if ($comments->isEmpty())
                                <p>Aucun commentaire trouvé.</p>
                            @endif
                        </div>

                        <!-- Link to View All Comments -->
                        <div class="mt-4">
                            <a href="{{ route('profile.comments', ['username' => $user->name]) }}" class="text-blue-600 hover:underline">
                                Voir tous les commentaires
                            </a>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</x-app-layout>

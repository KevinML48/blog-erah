<x-app-layout>
    <x-slot name="header">
        {{ __('Admin Dashboard') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome message -->
            <div class="erah-box">
                <div class="max-w-xl">
                    {{ __("You're an admin!") }}
                </div>
            </div>

            <!-- Users list -->
            <div class="erah-box">
                <div class="p-6">
                    <h2 class="mt-6 text-lg font-semibold">10 Derniers Inscrits</h2>
                    <table class="min-w-full mt-4 border border-gray-200">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">{{ __('Nom') }}</th>
                            <th class="py-2 px-4 border-b">{{ __('Email') }}</th>
                            <th class="py-2 px-4 border-b">{{ __('Inscrit le') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            @include('admin.partials.user', ['user' => $user])
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Posts -->
            <div class="erah-box">
                <h2 class="mt-6 text-lg font-semibold">Posts</h2>
                <div class="mt-4">
                    <a href="{{ route('admin.posts.create') }}" class="py-2 px-4 erah-link">
                        Créer Nouveau Post
                    </a>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold">10 Derniers Posts</h3>
                    <div class="space-y-4 mt-4">
                        @foreach($posts as $post)
                            @include('admin.partials.post', ['post' => $post])
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg bg-black font-semibold">Posts non publiés</h3>
                    <div class="space-y-4 mt-4">
                        @foreach($unpublishedPosts as $post)
                            @include('admin.partials.post', ['post' => $post])
                        @endforeach
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>

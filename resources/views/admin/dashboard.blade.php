<x-app-layout>
    <x-slot name="title">
        {{ __('Admin Dashboard') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Admin Dashboard') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome message -->
            <div class="erah-box">
                <div class="max-w-xl">
                    {{ __("You're an admin!") }}
                    <a href="{{ route('admin.delete.orphans') }}" class="erah-link">Delete Orphaned comments</a>
                </div>
            </div>

            <!-- Themes list -->
            <div class="erah-box">
                <h2 class="mt-6 text-lg font-semibold">Thèmes</h2>
                <div class="mt-4">
                    <a href="{{ route('admin.themes.create') }}" class="py-2 px-4 erah-link">
                        Créer Nouveau Thème
                    </a>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Tous les Thèmes</h3>
                    <div class="space-y-4 mt-4">
                        <table class="min-w-full mt-4 border border-gray-200">
                            <thead>
                            <tr class="text-left">
                                <th class="px-6 py-3 text-sm font-medium text-gray-500">Nom</th>
                                <th class="px-6 py-3 text-sm font-medium text-gray-500">Slug</th>
                                <th class="px-6 py-3 text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($themes as $theme)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $theme->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $theme->slug }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.themes.edit', $theme) }}" class="text-blue-600 hover:text-blue-900">Éditer</a>

                                        <form action="{{ route('admin.themes.destroy', $theme) }}" method="POST" class="inline-block ml-2" onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script>
                function confirmDelete() {
                    return confirm("Tous les posts seront supprimés. Continuer?");
                }
            </script>


            <!-- Users list -->
            <div class="erah-box">
                <div class="p-6">
                    <form id="searchForm">
                        <x-text-input
                                id="search"
                                name="search"
                                type="text"
                                placeholder="nom ou email"
                                :value="old('search')"
                                autocomplete="search"
                        />

                        <select name="role" id="role" class="mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">All Roles</option>
                            <option value="user">User</option>
                            <option value="ultra">Ultra</option>
                            <option value="admin">Admin</option>
                        </select>

                        <x-primary-button class="mt-4">
                            {{ __('Rechercher') }}
                        </x-primary-button>
                        <a href="{{ route('admin.users.search') }}" class="erah-link">Page de recherche</a>
                    </form>
                </div>

                <div class="p-6">
                    <h2 id="resultsTitle" class="mt-6 text-lg font-semibold">10 Derniers Inscrits</h2>

                    <table class="min-w-full mt-4 border border-gray-200">
                        <thead>
                        <tr class="text-left">
                            @include('admin.partials.user-table-head')
                        </tr>
                        </thead>
                        <tbody id="results">
                        <!-- Users list will be dynamically updated here -->
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
                            @include('posts.partials.post-short', ['post' => $post])
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg bg-black font-semibold">Posts non publiés</h3>
                    <div class="space-y-4 mt-4">
                        @foreach($unpublishedPosts as $post)
                            @include('posts.partials.post-short', ['post' => $post])
                        @endforeach
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script src="{{ asset('js/user-search.js') }}" defer></script>
</x-app-layout>

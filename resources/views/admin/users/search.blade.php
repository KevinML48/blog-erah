<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="erah-box">
                <div class="p-6">
                    <!-- Search Form -->
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
                    </form>
                </div>

                <div class="p-6">
                    <h2 class="mt-6 text-lg font-semibold" id="resultsTitle">{{ $users->total() }} Résultats</h2>
                    <table class="min-w-full mt-4 border border-gray-200">
                        <thead>
                        <tr class="text-left">
                            <th class="py-2 px-4 border-b">{{ __('Nom') }}</th>
                            <th class="py-2 px-4 border-b">{{ __('Email') }}</th>
                            <th class="py-2 px-4 border-b">{{ __('Role') }}</th>
                            <th class="py-2 px-4 border-b">{{ __('Inscrit le') }}</th>
                        </tr>
                        </thead>
                        <tbody id="results">
                        @foreach($users as $user)
                            @include('admin.partials.user', ['user' => $user])
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

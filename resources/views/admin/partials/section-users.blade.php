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

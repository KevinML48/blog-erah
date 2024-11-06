<thead>
<tr class="text-left">
    <!-- Name Column -->
    <th class="py-2 px-4 border-b">
        <a href="{{ route('admin.users.search', [
                'sort' => 'name',
                'direction' => request('direction') == 'asc' ? 'desc' : 'asc',
                'search' => request('search'),
                'role' => request('role')
            ]) }}" class="flex items-center space-x-1">
            {{ __('Nom') }}
            <span class="text-xs ml-2">
                    @if(request('sort') == 'name')
                    {{ request('direction') == 'asc' ? '↓' : '↑' }}
                @endif
                </span>
        </a>
    </th>

    <!-- Email Column -->
    <th class="py-2 px-4 border-b">
        <a href="{{ route('admin.users.search', [
                'sort' => 'email',
                'direction' => request('direction') == 'asc' ? 'desc' : 'asc',
                'search' => request('search'),
                'role' => request('role')
            ]) }}" class="flex items-center space-x-1">
            {{ __('Email') }}
            <span class="text-xs ml-2">
                    @if(request('sort') == 'email')
                    {{ request('direction') == 'asc' ? '↓' : '↑' }}
                @endif
                </span>
        </a>
    </th>

    <!-- Role Column -->
    <th class="py-2 px-4 border-b">
        <a href="{{ route('admin.users.search', [
                'sort' => 'role',
                'direction' => request('direction') == 'asc' ? 'desc' : 'asc',
                'search' => request('search'),
                'role' => request('role')
            ]) }}" class="flex items-center space-x-1">
            {{ __('Role') }}
            <span class="text-xs ml-2">
                    @if(request('sort') == 'role')
                    {{ request('direction') == 'asc' ? '↓' : '↑' }}
                @endif
                </span>
        </a>
    </th>

    <!-- Created At Column -->
    <th class="py-2 px-4 border-b">
        <a href="{{ route('admin.users.search', [
                'sort' => 'created_at',
                'direction' => request('direction') == 'asc' ? 'desc' : 'asc',
                'search' => request('search'),
                'role' => request('role')
            ]) }}" class="flex items-center space-x-1">
            {{ __('Inscrit le') }}
            <span class="text-xs ml-2">
                    @if(request('sort') == 'created_at')
                    {{ request('direction') == 'asc' ? '↓' : '↑' }}
                @endif
                </span>
        </a>
    </th>

    <!-- Delete Column -->
    <th class="py-2 px-4 border-b">{{ __('Supprimer') }}</th>
</tr>
</thead>

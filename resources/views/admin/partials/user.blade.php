<tr>
    <td class="py-2 px-4 border-b">
        <a href="{{ route('profile.show', ['username' => $user->name]) }}" class="hover:underline">
            {{ $user->name }}
        </a>
    </td>
    <td class="py-2 px-4 border-b">{{ $user->email }}</td>
    <td class="py-2 px-4 border-b">
        <x-role-span :role="$user->role">
            {{ ucfirst($user->role) }}
        </x-role-span>

        <div class="flex space-x-2 mt-1">
            @foreach(['user', 'ultra', 'admin'] as $role)
                @if($role !== $user->role)
                    <a href="{{ route('admin.users.changeRole', ['user' => $user->id, 'role' => $role, 'search' => request()->query('search'), 'page' => request()->query('page')]) }}"
                       class="text-blue-500 hover:underline">
                        {{ ucfirst($role) }}
                    </a>
                @endif
            @endforeach
        </div>
    </td>
    <td class="py-2 px-4 border-b">
        <span class="convert-time" data-time="{{ $user->created_at->toIso8601String() }}">
            <!-- Placeholder that will be replaced by JavaScript -->
        </span>
    </td>
</tr>

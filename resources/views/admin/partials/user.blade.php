<tr>
    <td class="py-2 px-4 border-b">
        <a href="{{ route('profile.show', ['username' => $user->name]) }}" class="text-gray-600 hover:underline">
            {{ $user->name }}
        </a>
    </td>
    <td class="py-2 px-4 border-b">{{ $user->email }}</td>
    <td class="py-2 px-4 border-b">
        <span class="convert-time" data-time="{{ $user->created_at->toIso8601String() }}">
            <!-- Placeholder that will be replaced by JavaScript -->
        </span>
    </td>
</tr>

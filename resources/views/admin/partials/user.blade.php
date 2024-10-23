<tr>
    <td class="py-2 px-4 border-b">
        <a href="{{ route('profile.show', ['username' => $user->name]) }}" class="text-gray-600 hover:underline">
            {{ $user->name }}
        </a>
    </td>
    <td class="py-2 px-4 border-b">{{ $user->email }}</td>
    <td class="py-2 px-4 border-b">{{ $user->created_at->format('Y-m-d H:i') }}</td>
</tr>

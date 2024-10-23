Par
<a href="{{ route('profile.show', ['username' => $post->user->name]) }}" class="text-blue-600 hover:underline">
    {{ $post->user->name }}
</a>
le {{ $post->created_at->format('d-m-Y H:i') }}

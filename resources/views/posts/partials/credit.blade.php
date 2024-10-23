Par
<a href="{{ route('profile.show', ['username' => $post->user->name]) }}" class="text-blue-600 hover:underline">
    {{ $post->user->name }}
</a>
le
<span class="convert-time" data-time="{{ $post->created_at->toIso8601String() }}">
    <!-- Placeholder that will be replaced by JavaScript -->
</span>

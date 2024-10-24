Par
<a href="{{ route('profile.show', ['username' => $post->user->name]) }}" class="text-blue-600 hover:underline">
    {{ $post->user->name }}
</a>
le
<span class="convert-time" data-time="{{ $post->publication_time ? $post->publication_time->toIso8601String() : $post->created_at->toIso8601String() }}">
    <!-- Placeholder that will be replaced by JavaScript -->
</span>

@if (($post->publication_time && $post->publication_time->isBefore($post->updated_at)) ||
    (!$post->publication_time && $post->created_at->isBefore($post->updated_at)))
    Edité le
    <span class="convert-time" data-time="{{ $post->updated_at->toIso8601String() }}">
        <!-- Placeholder that will be replaced by JavaScript -->
    </span>
@endif
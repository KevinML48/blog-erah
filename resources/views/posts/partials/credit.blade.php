<span>Par</span>
<a href="{{ route('profile.show', ['username' => $post->user->username]) }}" class="erah-link">
    {{ $post->user->name }}
</a>
<span>le</span>
<span class="convert-time"
      data-time="{{ $post->publication_time ? $post->publication_time->toIso8601String() : $post->created_at->toIso8601String() }}">
    <!-- Placeholder that will be replaced by JavaScript -->
</span>

@if (($post->publication_time && $post->publication_time->isBefore($post->updated_at)) ||
    (!$post->publication_time && $post->created_at->isBefore($post->updated_at)))
    <span>Édité le</span>
    <span class="convert-time" data-time="{{ $post->updated_at->toIso8601String() }}">
        <!-- Placeholder that will be replaced by JavaScript -->
    </span>
@endif

@if ($post->publication_time && $post->publication_time->isFuture())
    <span>Non publié</span>
@endif

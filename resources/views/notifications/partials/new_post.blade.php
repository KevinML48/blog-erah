<div class="new-post-notification">
    <strong>{{ $post->user->name }}</strong> a publié un nouvel article intitulé
    <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a> dans le thème
    <strong>{{ $post->theme->name }}</strong>.
    <div class="convert-time text-sm"
         data-time="{{ $post->publication_time->toIso8601String() }}">
        <!-- Placeholder that will be replaced by JavaScript -->
    </div>
</div>

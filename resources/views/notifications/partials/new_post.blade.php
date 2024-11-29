<div class="new-post-notification">
    <strong>{{ $post->user->name }}</strong> {!! __('notifications.new-post') !!}
    <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a> {!! __('notifications.about') !!}
    <strong>{{ $post->theme->name }}</strong>.
    <div class="convert-time text-sm"
         data-time="{{ $post->publication_time->toIso8601String() }}">
        <!-- Placeholder that will be replaced by JavaScript -->
    </div>
</div>

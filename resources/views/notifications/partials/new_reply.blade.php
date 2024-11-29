<div class="new-reply-notification">
    <strong>{{ $comment->content->user->name }}</strong> {!! __('notifications.replied') !!}
    @include('posts.partials.comment', ['content' => $comment, 'showMedia' => false])
</div>

<div class="new-reply-notification">
    <strong>{{ $comment->content->user->name }}</strong> a répondu à votre commentaire
    @include('posts.partials.comment', ['content' => $comment, 'showMedia' => false])
</div>

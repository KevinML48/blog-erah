<div class="p-6">
    <h3 class="text-lg font-semibold">Notifications de blog</h3>
    <form method="POST" action="{{ route('notifications.preferences.update') }}" class="space-y-2">
        @csrf
        @method('PUT')

        <!-- Post Published Preferences -->
        <div>
            @foreach ($themes as $theme)
                <div class="flex items-center">
                    <input type="checkbox" name="post_preferences[{{ $theme->id }}]"
                           id="theme-{{ $theme->id }}"
                           @if( !isset($postPreferences[$theme->id]) || $postPreferences[$theme->id]->is_enabled) checked @endif>
                    <label for="theme-{{ $theme->id }}" class="ml-2">{{ $theme->name }}</label>
                </div>
            @endforeach
        </div>

        <h3 class="text-lg font-semibold mt-8">Notifications de commentaires</h3>

        <!-- Reply Notifications -->
        <div class="flex items-center">
            <input type="checkbox" name="reply_notifications_enabled" id="reply_notifications"
                   @if( !isset($replyPreferences[null]) || $replyPreferences[null]->is_enabled) checked @endif>
            <label for="reply_notifications" class="ml-2">Recevoir des notifications de réponses à vos
                commentaires</label>
        </div>

        <!-- Like Notifications -->
        <div class="flex items-center">
            <input type="checkbox" name="like_notifications_enabled" id="like_notifications"
                   @if( !isset($likePreferences[null]) ||  $likePreferences[null]->is_enabled )  checked @endif>
            <label for="like_notifications" class="ml-2">Recevoir des notifications de j'aime sur vos
                commentaires</label>
        </div>

        <div>
            <button type="submit" class="erah-button">Mettre à jour les préférences
            </button>
        </div>
    </form>
</div>

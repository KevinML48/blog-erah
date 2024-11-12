<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Préférences de notification</h2>

                    <!-- Post Published Preferences -->
                    <h3 class="text-lg font-semibold">Notifications par thème (Postes publiés)</h3>
                    <form method="POST" action="{{ route('user.notification.preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            @foreach ($themes as $theme)
                                <div class="flex items-center">
                                    <input type="checkbox" name="post_preferences[{{ $theme->id }}]" id="theme-{{ $theme->id }}"
                                           @if( !isset($postPreferences[$theme->id]) || $postPreferences[$theme->id]->is_enabled) checked @endif>
                                    <label for="theme-{{ $theme->id }}" class="ml-2">{{ $theme->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <h3 class="text-lg font-semibold mt-8">Notifications globales</h3>

                        <!-- Reply Notifications -->
                        <div class="flex items-center">
                            <input type="checkbox" name="reply_notifications_enabled" id="reply_notifications"
                                   @if( !isset($replyPreferences[null]) || $replyPreferences[null]->is_enabled) checked @endif>
                            <label for="reply_notifications" class="ml-2">Recevoir des notifications de réponses à vos commentaires</label>
                        </div>

                        <!-- Like Notifications -->
                        <div class="flex items-center">
                            <input type="checkbox" name="like_notifications_enabled" id="like_notifications"
                                   @if( !isset($likePreferences[null]) ||  $likePreferences[null]->is_enabled )  checked @endif>
                            <label for="like_notifications" class="ml-2">Recevoir des notifications de j'aime sur vos commentaires</label>
                        </div>

                        <button type="submit" class="mt-6 bg-blue-500 px-4 py-2 rounded">Mettre à jour les préférences</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

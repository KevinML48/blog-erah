<x-app-layout>
    <x-slot name="header">
        {{ __('Préférences de notifications') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Préférences de notifications') }}
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="erah-box">
                <a href="{{ route('profile.edit') }}" class="erah-link-amnesic">
                    Retours au profil ←
                </a>
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Notifications de blog</h3>
                    <form method="POST" action="{{ route('notifications.preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Post Published Preferences -->
                        <div class="space-y-4">
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

                        <button type="submit" class="mt-6 bg-blue-500 px-4 py-2 rounded">Mettre à jour les préférences
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Nom') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Choisissez un nom d'affichage visible pour les autres utilisateurs.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update.description') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('description', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'description-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>

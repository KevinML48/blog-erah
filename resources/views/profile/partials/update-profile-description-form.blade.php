<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('profile.form.description.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("profile.form.description.details") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update.description') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('profile.user.name')" />
            <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('description', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div>
            <x-input-label for="description" :value="__('profile.user.description')" />
            <x-text-area id="description" name="description" class="mt-1 block w-full" :value="old('description', $user->description)" autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div class="flex items-center gap-4">
            <x-button.primary>{{ __('profile.save') }}</x-button.primary>

            @if (session('status') === 'description-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('profile.saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('profile.form.delete.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('profile.form.delete.details') }}
        </p>
    </header>

    <x-button.danger
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('profile.form.delete.button') }}</x-button.danger>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('profile.form.delete.confirm') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('profile.form.delete.warning') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('profile.user.password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('profile.user.password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-button.secondary x-on:click="$dispatch('close')">
                    {{ __('profile.form.cancel') }}
                </x-button.secondary>

                <x-button.danger class="ms-3">
                    {{ __('profile.form.delete.account') }}
                </x-button.danger>
            </div>
        </form>
    </x-modal>
</section>

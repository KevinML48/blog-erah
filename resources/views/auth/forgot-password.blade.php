<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 erah-box">
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation de mot de passe qui vous permettra de en choisir un nouveau.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')"/>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')"/>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                                  required autofocus/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button.primary>
                        {{ __('Lien de réinitialisation d\'e-mail') }}
                    </x-button.primary>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

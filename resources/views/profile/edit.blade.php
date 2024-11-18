<x-app-layout>
    <x-slot name="header">
        {{ __('Profil') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Profil') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="erah-box">
                <div class="max-w-xl">
                    @include('profile.partials.profile-picture')
                </div>
            </div>

            <div class="erah-box">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="erah-box">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="erah-box">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

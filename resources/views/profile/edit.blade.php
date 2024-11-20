<x-app-layout>
    <x-slot name="header">
        {{ __('Édition de Profil') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Édition de Profil') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Show Profile -->
            <div class="erah-box">
                <div class="max-w-xl">
                    @include('profile.partials.show-profile')
                </div>
            </div>

            <div class="flex flex-wrap lg:flex-nowrap space-x-6">
                <div class="lg:w-1/3 flex flex-col space-y-6">
                    <!-- Profile Picture -->
                    <div class="erah-box flex-1">
                        <div class="max-w-xl">
                            @include('profile.partials.profile-picture')
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="erah-box flex-1">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-description-form')
                        </div>
                    </div>
                </div>

                <div class="flex flex-col space-y-6 lg:w-2/3">
                    <!-- Profile Informations -->
                    <div class="erah-box flex-1">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="erah-box flex-1">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>


            <!-- Notification Preferences -->
            <div class="erah-box">
                <div class="max-w-xl">
                    @include('profile.partials.notifications-preferences')
                </div>
            </div>

            <!-- Account deletion -->
            <div class="erah-box">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

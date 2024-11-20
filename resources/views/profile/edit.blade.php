<x-app-layout>
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

            <!-- Navigation for Small Screens -->
            <div class="md:hidden">
                <div x-data="{ activeSection: 'profile' }">
                    <!-- Navigation Buttons -->
                    <div class="flex space-x-2 mb-4">
                        <button
                            @click="activeSection = 'profile'"
                            :class="{ 'ring-2 ring-white ring-offset-2': activeSection === 'profile' }"
                            class="erah-button">
                            Profil
                        </button>
                        <button
                            @click="activeSection = 'account'"
                            :class="{ 'ring-2 ring-white ring-offset-2': activeSection === 'account' }"
                            class="erah-button">
                            Compte
                        </button>
                        <button
                            @click="activeSection = 'notifications'"
                            :class="{ 'ring-2 ring-white ring-offset-2': activeSection === 'notifications' }"
                            class="erah-button">
                            Notifications
                        </button>
                    </div>

                    <!-- Profile Section -->
                    <div x-show="activeSection === 'profile'" class="space-y-6">
                        <!-- Profile Picture -->
                        <div class="erah-box">
                            <div class="max-w-xl">
                                @include('profile.partials.profile-picture')
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="erah-box mt-6">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-description-form')
                            </div>
                        </div>
                    </div>

                    <!-- Account Section -->
                    <div x-show="activeSection === 'account'" class="space-y-6">
                        <!-- Profile Information -->
                        <div class="erah-box">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="erah-box mt-6">
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        <!-- Account Deletion -->
                        <div class="erah-box mt-6">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Section (Hidden on small screens by default) -->
                    <div x-show="activeSection === 'notifications'" class="space-y-6">
                        <!-- Notification Preferences -->
                        <div class="erah-box">
                            <div class="max-w-xl">
                                @include('profile.partials.notifications-preferences')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content for Larger Screens -->
            <div class="hidden md:block overflow-x-auto space-y-6 md:overflow-visible mx-auto md:max-w-7xl">
                <div class="flex space-x-6 snap-proximity snap-x scroll-smooth">

                    <div class=" shrink-0 md:shrink w-screen md:w-1/3 flex flex-col">
                        <!-- Profile Picture -->
                        <div class="erah-box flex-1">
                            <div class="max-w-xl">
                                @include('profile.partials.profile-picture')
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="erah-box mt-6 flex-1">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-description-form')
                            </div>
                        </div>
                    </div>

                    <div class=" flex-shrink-0 w-screen md:w-2/3 flex flex-col">
                        <!-- Profile Information -->
                        <div class="erah-box flex-1">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        <div class="erah-box mt-6 flex-1">
                            <!-- Password -->
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Notification Preferences (Hidden on Small Screens) -->
            <div class="erah-box mt-6 hidden md:block">
                <div class="max-w-xl">
                    @include('profile.partials.notifications-preferences')
                </div>
            </div>

            <!-- Account Deletion (Hidden on Small Screens) -->
            <div class="erah-box mt-6 hidden md:block">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>


    </div>
</x-app-layout>

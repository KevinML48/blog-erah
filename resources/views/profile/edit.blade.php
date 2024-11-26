<x-app-layout>
    <x-slot name="title">
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

            <!-- Navigation for Small Screens -->
            <div class="md:hidden">
                <x-navigator
                    :default="'profile'"
                    :triggers="[
                        'profile' => view('components.navigator-trigger', ['trigger' => 'profile', 'label' => 'Profil']),
                        'account' => view('components.navigator-trigger', ['trigger' => 'account', 'label' => 'Compte']),
                        'notifications' => view('components.navigator-trigger', ['trigger' => 'notifications', 'label' => 'Notifications']),
                    ]"
                    :sections="[
                        'profile' => view('profile.partials.section-profile', ['user' => $user]),
                        'account' => view('profile.partials.section-account', ['user' => $user]),
                        'notifications' => view('profile.partials.section-notifications-settings', ['themes' => $themes]),
                    ]"
                />
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

        @if (session('first_time_name') === 'true')
            <!-- Modal Background (optional, if you want to dim the background, this can be removed) -->
            <div id="modalBackdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50"></div>

            <!-- Modal Content -->
            <div id="nameModal" class="fixed inset-0 flex justify-center items-center z-50">
                <div class="erah-box p-6 rounded-lg shadow-lg w-96">
                    <!-- Include the form partial -->
                    @include('profile.partials.update-profile-name-form')
                </div>
            </div>

            <script>
                // Show the modal when the page loads
                window.onload = function() {
                    // Show modal by setting display to 'flex'
                    document.getElementById('nameModal').style.display = 'flex';

                    // Show the backdrop (optional if you want dimmed background)
                    document.getElementById('modalBackdrop').style.display = 'block';
                };
            </script>
        @endif



    </div>
</x-app-layout>

<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 erah-box">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Username -->
                <div class="mt-4 relative" x-data="checkAvailability()">
                    <x-input-label for="username" :value="__('Nom d\'utilisateur')"/>
                    <p class="mt-1 text-sm text-gray-400">
                        {{ __("Votre nom d'utilisateur doit être unique.") }}
                    </p>
                    <div class="relative">
                        <x-text-input id="username" class="block mt-1 w-full pr-10" type="text" name="username"
                                      x-model="username" :value="old('username')"
                                      @input.debounce.500ms="checkUsername" required autofocus autocomplete="name"/>
                        <!-- Checkmark or Error Icon -->
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <!-- Length Check -->
                            <template x-if="username && (username.length < 3 || username.length > 15)">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </template>

                            <!-- Checking Spinner -->
                            <template
                                x-if="!isCheckingUsername && username.length >= 3 && username.length <= 15 && isCheckingUsername">
                                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v4l5-5-5-5v4a10 10 0 1010 10h-4l5 5-5 5v-4a8 8 0 01-8 8"></path>
                                </svg>
                            </template>

                            <!-- Valid Username -->
                            <template
                                x-if="!isCheckingUsername && username && username.length >= 3 && username.length <= 15 && !usernameExists && username.match(/^[a-zA-Z0-9_-]+$/)">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </template>

                            <!-- Invalid Username -->
                            <template x-if="!isCheckingUsername && usernameExists">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </template>

                            <!-- Invalid Regex Username -->
                            <template x-if="!isCheckingUsername && username.length >= 3 && username.length <= 15 && username && !username.match(/^[a-zA-Z0-9_-]+$/)">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </template>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-2"/>

                    <!-- Username Validation Message -->
                    <template x-if="username && username.length < 3">
                        <p class="mt-2 text-xs text-red-500">Le nom d'utilisateur doit comporter au moins 3 caractères.</p>
                    </template>
                    <template x-if="username && username.length > 15">
                        <p class="mt-2 text-xs text-red-500">Le nom d'utilisateur ne doit pas dépasser 15 caractères.</p>
                    </template>
                    <template x-if="username && username.length >= 3 && username.length <= 15 && !username.match(/^[a-zA-Z0-9_-]+$/)">
                        <p class="mt-2 text-xs text-red-500">Le nom d'utilisateur ne doit contenir que des lettres, des chiffres, des tirets et des underscores.</p>
                    </template>
                    <template x-if="usernameExists">
                        <p class="mt-2 text-xs text-red-500">Ce nom d'utilisateur n'est pas disponible.</p>
                    </template>
                </div>


                <!-- Email -->
                <div class="mt-4 relative" x-data="checkAvailability()">
                    <x-input-label for="email" :value="__('Email')"/>
                    <div class="relative">
                        <x-text-input id="email" class="block mt-1 w-full pr-10" type="email" name="email"
                                      x-model="email" :value="old('email')"
                                      @input.debounce.500ms="checkEmail" required autocomplete="username"/>

                        <!-- Checkmark or Error Icon -->
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <!-- Invalid Email Format (Red X) -->
                            <template x-if="email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </template>

                            <!-- Checking Spinner -->
                            <template x-if="isCheckingEmail">
                                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v4l5-5-5-5v4a10 10 0 1010 10h-4l5 5-5 5v-4a8 8 0 01-8 8"></path>
                                </svg>
                            </template>

                            <!-- Valid Email -->
                            <template x-if="!isCheckingEmail && email && !emailExists && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </template>

                            <!-- Invalid Email (Already Exists) -->
                            <template x-if="!isCheckingEmail && emailExists">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </template>
                        </div>
                    </div>

                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>

                    <!-- Email Format Validation Message -->
                    <template x-if="email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)">
                        <p class="mt-2 text-xs text-red-500">L'email n'est pas valide.</p>
                    </template>

                    <!-- Email Exists Message -->
                    <template x-if="emailExists">
                        <p class="mt-2 text-xs text-red-500">Cet email n'est pas disponible.</p>
                    </template>
                </div>



                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Mot de passe')"/>

                    <x-text-input id="password" class="block mt-1 w-full"
                                  type="password"
                                  name="password"
                                  required autocomplete="new-password"/>

                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')"/>

                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                  type="password"
                                  name="password_confirmation" required autocomplete="new-password"/>

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                       href="{{ route('login') }}">
                        {{ __('Déjà inscrit ?') }}
                    </a>

                    <x-button.primary class="ms-4">
                        {{ __('S\'inscrire') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkAvailability', () => ({
            username: '',
            email: '',
            usernameExists: false,
            emailExists: false,
            isCheckingUsername: false,
            isCheckingEmail: false,

            async checkUsername() {
                if (this.username.length < 3 || this.username.length > 15) {
                    this.usernameExists = false; // Reset state for short/long usernames
                    return;
                }

                this.isCheckingUsername = true;
                this.usernameExists = false;

                try {
                    const response = await fetch(`/check-username?username=${encodeURIComponent(this.username)}`);
                    if (response.ok) {
                        const data = await response.json();
                        this.usernameExists = data.exists;
                    } else {
                        console.error('Error checking username:', response.statusText);
                    }
                } catch (error) {
                    console.error('Error fetching username availability:', error);
                }

                this.isCheckingUsername = false;
            },

            async checkEmail() {
                // Basic email format validation using regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.email)) {
                    this.emailExists = false;
                    return;
                }

                this.isCheckingEmail = true;
                this.emailExists = false;

                try {
                    const response = await fetch(`/check-email?email=${encodeURIComponent(this.email)}`);
                    if (response.ok) {
                        const data = await response.json();
                        this.emailExists = data.exists;
                    } else {
                        console.error('Error checking email:', response.statusText);
                    }
                } catch (error) {
                    console.error('Error fetching email availability:', error);
                }

                this.isCheckingEmail = false;
            }

        }));
    });
</script>

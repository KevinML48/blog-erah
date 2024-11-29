<x-app-layout>
    <x-slot name="title">
        {{ __('Créer Thème') }}
    </x-slot>

    <x-slot name="header">
        {{ __('Créer Thème') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="erah-box">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.themes.create') }}">
                        @csrf
                        <div class="space-y-6" x-data="{
                            name: '',
                            slug: '',
                            generateSlug() {
                                this.slug = this.name
                                    .toLowerCase() // Make lowercase
                                    .replace(/[^\w\s-]/g, '') // Remove non-alphanumeric characters
                                    .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with dashes
                                    .replace(/^-+|-+$/g, ''); // Trim dashes from the ends
                            }
                        }">
                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                placeholder="Nom"
                                :value="old('name')"
                                required
                                x-model="name"
                                x-on:input="generateSlug"
                            />

                            <x-text-input
                                id="slug"
                                name="slug"
                                type="text"
                                placeholder="Slug"
                                :value="old('slug')"
                                x-model="slug"
                            />

                            <x-button.primary>
                                {{ __('Créer') }}
                            </x-button.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

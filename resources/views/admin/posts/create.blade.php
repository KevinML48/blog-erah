<x-app-layout>
    <x-slot name="title">
        {{ __('Créer Post') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Créer Post') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="erah-box">

                <!-- Create Form -->
                <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
                    @include('admin.partials.form')
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

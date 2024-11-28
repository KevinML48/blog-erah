<x-app-layout>
    <x-slot name="title">
        {{ __('Admin Dashboard') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Admin Dashboard') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-navigator
                :default="'users'"
                :triggers="[
                    'users' => view('components.navigator-trigger', ['trigger' => 'users', 'label' => 'Utilisateurs']),
                    'posts' => view('components.navigator-trigger', ['trigger' => 'posts', 'label' => 'Posts']),
                    'themes' => view('components.navigator-trigger', ['trigger' => 'themes', 'label' => 'Thèmes']),
                    ]"
                :sections="[
                    'users' => view('admin.partials.section-users', ['users' => $users]),
                    'posts' => view('admin.partials.section-posts', ['posts' => $posts, 'unpublishedPosts' => $unpublishedPosts]),
                    'themes' => view('admin.partials.section-themes', ['themes' => $themes]),
                    ]"
            />
        </div>
    </div>

    <script src="{{ asset('js/user-search.js') }}" defer></script>
</x-app-layout>

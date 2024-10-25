<x-app-layout>
    <x-slot name="header">
        {{ $user->name }}
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if($user->profile_picture)
                <div class="erah-box">
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture"
                         class="w-24 h-24 rounded-full object-fill">
                </div>
            @endif


            <div class="erah-box">
                <div class="p-6">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>S'est inscrit le :</strong> {{ $user->created_at->format('d M, Y') }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

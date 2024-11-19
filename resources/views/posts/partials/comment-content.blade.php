<!-- Dropdown menu -->
<x-dropdown align="right" width="fit" triggerType="hover" flex="row">
    <!-- Dropdown button -->
    <x-slot name="trigger">
        <!-- Dropdown content -->
        <x-slot name="content">

            <!-- Follow/Unfollow button -->
            @if(auth()->user() != $content->user)
                <div class="ml-1">
                    <div id="unfollow-button-{{ $content->user->id }}" class="{{ auth()->user()->isFollowing($content->user) ? '' : 'hidden' }}">
                        <button
                                class="peer follow-button"
                                data-following="true"
                                aria-describedby="tooltip-unfollow"
                                onclick="unfollowUser({{ $content->user->id }})"
                                data-user-id="{{ $content->user->id }}">
                            <x-svg-user option="minus"></x-svg-user>
                        </button>
                        <div id="tooltip-unfollow" role="tooltip"
                             class="w-36 opacity-0 peer-hover:opacity-100 peer-focus:opacity-100 absolute bottom-full mb-2 start-auto -translate-x-1/2 z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700">
                            Arrêter de suivre {{ $content->user->name }}
                        </div>
                    </div>
                    <div id="follow-button-{{ $content->user->id }}" class="{{ auth()->user()->isFollowing($content->user) ? 'hidden' : '' }}">
                        <button
                                class="peer follow-button"
                                data-following="false"
                                aria-describedby="tooltip-follow"
                                onclick="followUser({{ $content->user->id }})"
                                data-user-id="{{ $content->user->id }}">
                            <x-svg-user option="plus"></x-svg-user>
                        </button>
                        <div id="tooltip-follow" role="tooltip"
                             class="w-36 opacity-0 peer-hover:opacity-100 peer-focus:opacity-100 absolute bottom-full mb-2 left-1/2 -translate-x-1/2 z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700">
                            Suivre {{ $content->user->name }}
                        </div>
                    </div>
                </div>
            @endif

            @auth
                @if (auth()->user()->id === $content->user->id || auth()->user()->isAdmin())
                    <!-- Delete button -->
                    <form action="{{ route('comments.destroy', $content->comment) }}" method="POST"
                          class="inline ml-1"
                          onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="peer text-red-600" aria-describedby="tooltip-delete">
                            <x-svg-bin></x-svg-bin>
                        </button>
                        <div id="tooltip-delete" role="tooltip"
                             class="w-36 opacity-0 peer-hover:opacity-100 peer-focus:opacity-100 absolute bottom-full mb-2 left-1/2 -translate-x-1/2 z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700">
                            Supprimer ce commentaire
                        </div>
                    </form>
                @endif

            @endauth
        </x-slot>


        <div class="py-2 p-2 flex flex-col shadow-md rounded-2xl
@if(request()->routeis('comments.show') && request()->route('comment.id') && request()->route('comment.id') == $content->comment->id )
 bg-red-950
 @else
 bg-gray-900
@endif
">
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-2">
                    <!-- Profile Picture -->
                    <div>
                        @if($content->user->profile_picture)
                            <img src="{{ asset('storage/' . $content->user->profile_picture) }}" alt="Profile Picture"
                                 class="w-12 h-12 rounded-full object-cover">
                        @else
                            <img src="{{ asset('storage/profile_pictures/default.png')}}" alt="Default Profile Picture"
                                 class="w-12 h-12 rounded-full object-cover">
                        @endif
                    </div>
                    <!-- Name -->
                    <div>
                        <a href="{{ route('profile.show', $content->user->name) }}"
                           class="erah-link font-bold text-left focus:outline-none">
                            <x-role-span :role="$content->user->role">
                                {{ $content->user->name }}
                            </x-role-span>
                        </a>
                    </div>
                </div>

                <div class="flex space-x-2 ml-auto">
                    @auth

                    @endauth

                    <!-- Creation Date -->
                    <div>
                        <a href="{{ route('comments.show', ['post' => $content->comment->post->id, 'comment' => $content->comment->id]) }}"
                           class="hover:underline">
                    <span class="text-gray-500 text-sm convert-time"
                          data-time="{{ $content->created_at->toIso8601String() }}">
                    </span>
                        </a>
                    </div>

                </div>
            </div>

            <!-- Body -->
            <div class="rounded py-2">
                <div class="py-2 ml-14">
                    <div id="content-preview-{{ $content->id }}"
                         class="max-h-36 overflow-hidden transition-all duration-300 ease-in-out">
                        {!! nl2br(e($content->body)) !!}
                    </div>
                    <span id="toggle-container-{{ $content->id }}" class="hidden">
                <button id="toggle-button-more-{{ $content->id }}" class="mt-2 text-blue-600 hover:underline"
                        onclick="showMore({{ $content->id }})">Dérouler</button>
                <button id="toggle-button-less-{{ $content->id }}" class="mt-2 text-blue-600 hover:underline hidden"
                        onclick="showLess({{ $content->id }})">Cacher</button>
            </span>
                </div>

                <!-- Media -->
                @if ($content->media && ($showMedia ?? true))
                    <div class="py-2 ml-14">
                        @if (filter_var($content->media, FILTER_VALIDATE_URL) && strpos($content->media, 'tenor.com') !== false)
                            <!-- If it's a Tenor GIF URL -->
                            <img src="{{ $content->media }}" alt="Comment GIF" class="object-contain h-48 w-48">
                        @else
                            <!-- If it's an uploaded image -->
                            <img src="{{ asset('storage/' . $content->media) }}" alt="Comment Image"
                                 class="object-contain h-48 w-48">
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </x-slot>
</x-dropdown>

<script>
    function confirmDelete() {
        return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contentPreviewElements = document.querySelectorAll('[id^="content-preview-"]');

        contentPreviewElements.forEach(function (contentPreview) {
            const toggleContainerId = contentPreview.id.replace('content-preview-', 'toggle-container-');
            const toggleContainer = document.getElementById(toggleContainerId);

            // Get the scroll height and offset height
            const contentHeight = contentPreview.scrollHeight;
            const visibleHeight = contentPreview.offsetHeight;

            // Check if the content exceeds the visible height
            if (contentHeight > visibleHeight) {
                toggleContainer.classList.remove('hidden'); // Show the button if there's more content
            }
        });
    });

    function showMore(contentId) {
        event.preventDefault();

        const contentPreview = document.getElementById(`content-preview-${contentId}`);
        const buttonMore = document.getElementById(`toggle-button-more-${contentId}`);
        const buttonLess = document.getElementById(`toggle-button-less-${contentId}`);

        // Store the current max height
        const currentMaxHeightClass = Array.from(contentPreview.classList).find(cls => cls.startsWith('max-h-'));

        // Expand the content
        contentPreview.classList.remove(currentMaxHeightClass);
        contentPreview.classList.add('max-h-screen');

        // Hide the "Show more" button and show the "Show less" button
        buttonMore.classList.add('hidden');
        buttonLess.classList.remove('hidden');

        // Store the max height in a data attribute
        contentPreview.dataset.originalMaxHeight = currentMaxHeightClass;
    }

    function showLess(contentId) {
        event.preventDefault();

        const contentPreview = document.getElementById(`content-preview-${contentId}`);
        const buttonMore = document.getElementById(`toggle-button-more-${contentId}`);
        const buttonLess = document.getElementById(`toggle-button-less-${contentId}`);

        // Get the original max height from the data attribute
        const originalMaxHeightClass = contentPreview.dataset.originalMaxHeight;

        // Collapse the content
        contentPreview.classList.remove('max-h-screen');
        contentPreview.classList.add(originalMaxHeightClass);

        // Show the "Show more" button and hide the "Show less" button
        buttonMore.classList.remove('hidden');
        buttonLess.classList.add('hidden');
    }
</script>

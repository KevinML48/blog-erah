<div class="py-2 p-2 flex flex-col border-b border-gray-300 bg-gray-900">
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
            <!-- Delete Link -->
            <div>
                @if (auth()->user() && (auth()->user()->id === $content->user->id || auth()->user()->isAdmin()))
                    <form action="{{ route('comments.destroy', $content->comment) }}" method="POST" class="inline"
                          onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>

            @if(auth()->user() != $content->user)
                <div>
                    <button id="unfollow-button-{{ $content->user->id }}"
                            class="follow-button {{ auth()->user()->isFollowing($content->user) ? '' : 'hidden' }}"
                            data-following="true"
                            onclick="unfollowUser({{ $content->user->id }})"
                            data-user-id="{{ $content->user->id }}">
                        Se désabonner
                    </button>
                    <button id="follow-button-{{ $content->user->id }}"
                            class="follow-button {{ auth()->user()->isFollowing($content->user) ? 'hidden' : '' }}"
                            data-following="false"
                            onclick="followUser({{ $content->user->id }})"
                            data-user-id="{{ $content->user->id }}">
                        S'abonner
                    </button>
                </div>
            @endif

            <!-- Creation Date -->
            <div>
                    <span class="text-gray-500 text-sm convert-time"
                          data-time="{{ $content->created_at->toIso8601String() }}">
                          {{ $content->created_at->diffForHumans() }}
                    </span>
            </div>
        </div>
    </div>

    <!-- Body -->
    <a href="{{ route('comments.show', ['post' => $content->comment->post->id, 'comment' => $content->comment->id]) }}"
       class="py-4">
        <div class="hover:bg-gray-800 rounded">
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
            @if ($content->media)
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
    </a>
</div>


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

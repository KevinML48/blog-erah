@auth
    <!-- Follow/Unfollow button -->
    @if(auth()->user() != $content->user)
        <div class="ml-1">
            <div id="{{$withDetails ? 'detailed-' : 'simple-'}}unfollow-button-{{ $content->user->id }}"
                 class="{{ auth()->user()->isFollowing($content->user) ? '' : 'hidden' }} {{$withDetails ? 'detailed-' : 'simple-'}}unfollow-button-{{ $content->user->id }}">
                <button
                    class="peer follow-button"
                    data-following="true"
                    aria-describedby="tooltip-unfollow"
                    onclick="unfollowUser({{ $content->user->id }})"
                    data-user-id="{{ $content->user->id }}">
                    <div class="flex items-end whitespace-nowrap space-x-2">
                        @if($withDetails)
                            <span>Arrêter de suivre {{ $content->user->name }}</span>
                        @endif
                        <x-svg.user option="minus"></x-svg.user>
                    </div>
                </button>
                @if(!$withDetails)
                    <div id="tooltip-unfollow" role="tooltip"
                         class="w-36 opacity-0 peer-hover:opacity-100 peer-focus:opacity-100 absolute bottom-full mb-2 start-auto -translate-x-1/2 z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700">
                        Arrêter de suivre {{ $content->user->name }}
                    </div>
                @endif
            </div>
            <div id="{{$withDetails? 'detailed-' : 'simple-'}}follow-button-{{ $content->user->id }}"
                 class="{{ auth()->user()->isFollowing($content->user) ? 'hidden' : '' }} {{$withDetails? 'detailed-' : 'simple-'}}follow-button-{{ $content->user->id }}">
                <button
                    class="peer follow-button"
                    data-following="false"
                    aria-describedby="tooltip-follow"
                    onclick="followUser({{ $content->user->id }})"
                    data-user-id="{{ $content->user->id }}">
                    <div class="flex items-end whitespace-nowrap space-x-2">
                        @if($withDetails)
                            <span>Suivre {{ $content->user->name }}</span>
                        @endif
                        <x-svg.user option="plus"></x-svg.user>
                    </div>
                </button>
                @if(!$withDetails)
                    <div id="tooltip-follow" role="tooltip"
                         class="w-36 opacity-0 peer-hover:opacity-100 peer-focus:opacity-100 absolute bottom-full mb-2 left-1/2 -translate-x-1/2 z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700">
                        Suivre {{ $content->user->name }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Mute/Unmute comment -->
    @if (auth()->user()->id === $content->user->id || auth()->user()->isAdmin())
        @if(auth()->user()->id === $content->user->id)
            <div class="ml-1">
                <div id="mute-button-{{ $content->id }}"
                     class="{{ auth()->user()->hasMuted($content) ? 'hidden' : '' }} {{$withDetails ? 'detailed-' : 'simple-'}}mute-button-{{ $content->id }}">
                    <button
                        class="peer follow-button"
                        data-following="true"
                        aria-describedby="tooltip-mute"
                        onclick="muteComment({{ $content->id }})"
                        data-user-id="{{ auth()->user()->id }}">
                        <div class="flex items-end whitespace-nowrap space-x-2">
                            @if($withDetails)
                                <span>Ne plus recevoir de notifications pour ce commentaire</span>
                            @endif
                            <x-svg.volume :mute="true"></x-svg.volume>
                        </div>
                    </button>
                    @if(!$withDetails)
                        <div id="tooltip-mute" role="tooltip"
                             class="w-36 opacity-0 peer-hover:opacity-100 peer-focus:opacity-100 absolute bottom-full mb-2 start-auto -translate-x-1/2 z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700">
                            Ne plus recevoir de notifications pour ce commentaire
                        </div>
                    @endif
                </div>
                <div id="unmute-button-{{ $content->id }}"
                     class="{{ auth()->user()->hasMuted($content) ? '' : 'hidden' }} {{$withDetails ? 'detailed-' : 'simple-'}}unmute-button-{{ $content->id }}">
                    <button
                        class="peer follow-button"
                        data-following="false"
                        aria-describedby="tooltip-follow"
                        onclick="unmuteComment({{ $content->id }})"
                        data-user-id="{{ auth()->user()->id }}">
                        <div class="flex items-end whitespace-nowrap space-x-2">
                            @if($withDetails)
                                <span>Recevoir des notifications pour ce commentaire</span>
                            @endif
                            <x-svg.volume option="plus"></x-svg.volume>
                        </div>
                    </button>
                    @if(!$withDetails)
                        <div id="tooltip-follow" role="tooltip"
                             class="w-36 opacity-0 peer-hover:opacity-100 peer-focus:opacity-100 absolute bottom-full mb-2 left-1/2 -translate-x-1/2 z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700">
                            Recevoir des notifications pour ce commentaire
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Delete button -->
        <form action="{{ route('comments.destroy', $content->comment) }}" method="POST"
              class="inline ml-1"
              onsubmit="return confirmDelete();">
            @csrf
            @method('DELETE')
            <button type="submit" class="peer text-red-600" aria-describedby="tooltip-delete">
                <div class="flex items-end flex-row whitespace-nowrap space-x-2">
                    @if($withDetails)
                        <span>Supprimer ce commentaire</span>
                    @endif
                    <x-svg.bin></x-svg.bin>
                </div>
            </button>
            @if(!$withDetails)
                <div id="tooltip-delete" role="tooltip"
                     class="w-36 opacity-0 peer-hover:opacity-100 peer-focus:opacity-100 absolute bottom-full mb-2 left-1/2 -translate-x-1/2 z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700">
                    Supprimer ce commentaire
                </div>
            @endif
        </form>
    @endif

    <!-- Nested dropdown -->
    @if(!$withDetails)
        <x-dropdown align="right" width="fit" triggerType="click" flex="col">
            <!-- Dropdown button -->
            <x-slot name="trigger">
                <button
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                </button>

                <!-- Dropdown content -->
                <x-slot name="content">
                    @include('posts.partials.dropdown-content', ['withDetails' => true])
                </x-slot>
            </x-slot>
        </x-dropdown>
    @endif
@endauth

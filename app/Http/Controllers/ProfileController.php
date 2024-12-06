<?php

namespace App\Http\Controllers;

use App\Helpers\ReservedUsernamesHelper;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Comment;
use App\Models\NotificationType;
use App\Models\Theme;
use App\Models\User;
use App\Services\CommentServiceInterface;
use App\Services\ProfileServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected ProfileServiceInterface $profileService;
    protected CommentServiceInterface $commentService;

    public function __construct(ProfileServiceInterface $profileService, CommentServiceInterface $commentService)
    {
        $this->profileService = $profileService;
        $this->commentService = $commentService;
    }

    /**
     * Display the specified user's profile.
     */
    public function show($username): View
    {


        $user = $this->profileService->getUserProfile($username);
        $comments = $this->profileService->getUserComments($user);
//        $likes = $this->profileService->getUserLikedComments($user);
        $likes = collect();
//        $postLikes = $this->profileService->getUserLikedPosts($user);
        $postLikes = collect();

        $this->commentService->addAuthUserTags([$comments, $likes], Auth::user());

        return view('profile.show', [
            'user' => $user,
            'comments' => $comments,
            'likes' => $likes,
            'postLikes' => $postLikes,
        ]);
    }

    public function fetchMoreComments($username, Request $request): JsonResponse
    {
        // Get the user based on the username
        $user = $this->profileService->getUserProfile($username);

        // Get the current page from the request (default to 1 if not provided)
        $page = $request->query('page', 1);

        // Get the next set of comment contents, paginated
        $comments = $this->profileService->getUserComments($user);
        $this->commentService->addAuthUserTags([$comments], Auth::user());
        // Check if there are more comments available (pagination)
        $hasMorePages = $comments->hasMorePages();

        // Return the new content and pagination info as JSON
        return response()->json([
            'content' => view('posts.partials.comment-loop', ['comments' => $comments, 'depth' => -1])->render(),
            'has_more_pages' => $hasMorePages  // Explicitly send has_more_pages for clarity
        ]);
    }

    public function fetchMoreLikedComments($username, Request $request): JsonResponse
    {
        $user = $this->profileService->getUserProfile($username);
        $page = $request->query('page', 1);

        // Get the next set of liked comments, paginated
        $likedComments = $this->profileService->getUserLikedComments($user);
        $this->commentService->addAuthUserTags([$likedComments], Auth::user());

        // Check if there are more liked comments available (pagination)
        $hasMorePages = $likedComments->hasMorePages();

        return response()->json([
            'content' => view('posts.partials.comment-loop', ['comments' => $likedComments, 'depth' => -1, 'emptyMessageKey' => 'comments.empty.likes'])->render(),
            'has_more_pages' => $hasMorePages,
            'next_page_url' => $hasMorePages ? $likedComments->nextPageUrl() : null,
        ]);
    }

    public function fetchMoreLikedPosts($username, Request $request): JsonResponse
    {
        // Get the user based on the username
        $user = $this->profileService->getUserProfile($username);

        // Get the current page from the request (default to 1 if not provided)
        $page = $request->query('page', 1);

        // Get the next set of liked posts, paginated
        $likedPosts = $this->profileService->getUserLikedPosts($user);

        // Check if there are more liked posts available (pagination)
        $hasMorePages = $likedPosts->hasMorePages();

        // Return the new content and pagination info as JSON
        return response()->json([
            'content' => view('posts.partials.posts-loop', ['posts' => $likedPosts, 'emptyMessageKey' => 'posts.empty.likes'])->render(),
            'has_more_pages' => $hasMorePages,
            'next_page_url' => $hasMorePages ? $likedPosts->nextPageUrl() : null,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $themes = Theme::all();

        // Get the specific notification types for post publication, reply, and like
        $postPublishedType = NotificationType::where('name', 'post_published')->first();
        $replyNotificationType = NotificationType::where('name', 'comment_reply')->first();
        $likeNotificationType = NotificationType::where('name', 'comment_like')->first();

        // Fetch user's preferences for post publication notifications, keyed by theme ID
        $postPreferences = auth()->user()
            ->notificationPreferences()
            ->where('notification_type_id', $postPublishedType->id)
            ->where('context_type', 'theme')
            ->get()
            ->keyBy('context_id');

        // Fetch user's preferences for replies (global setting)
        $replyPreferences = auth()->user()
            ->notificationPreferences()
            ->where('notification_type_id', $replyNotificationType->id)
            ->get()
            ->keyBy('context_id');

        // Fetch user's preferences for likes (global setting)
        $likePreferences = auth()->user()
            ->notificationPreferences()
            ->where('notification_type_id', $likeNotificationType->id)
            ->get()
            ->keyBy('context_id');

        $user = $request->user();

        return view('profile.edit', compact('user', 'themes', 'postPreferences', 'replyPreferences', 'likePreferences'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', __('message.profile.success.update'));
    }

    public function updateDescription(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'description' => ['max:255'],
        ]);

        $user = Auth::user();

        $user->name = $request->input('name');
        $user->description = $request->input('description');

        if ($user->isDirty('name') || $user->isDirty('description')) {
            $user->save();
            return back()->with('success', __('message.profile.success.update'));
        }

        return back()->with('status', 'no-changes-made');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        $this->profileService->deleteUserAccount($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Delete the user's account from the admin dashboard.
     */
    public function adminDestroy(User $user, Request $request): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.search')->with('error', __('message.profile.error.cannot_delete_own_account'));
        }

        $this->profileService->deleteUserAccount($user);

        return redirect()->route('admin.users.search')->with('success', __('message.profile.success.delete'));
    }

    /**
     * Update the user's account Profile Picture.
     */
    public function updateProfilePicture(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        $this->profileService->updateUserProfilePicture($user, $path);

        return redirect()->back()->with('success', __('message.profile.success.profile_picture_update'));
    }

    /**
     * Search and filter users.
     */
    public function search(Request $request): View|JsonResponse
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        $query->orderBy($sort, $direction);
        $users = $query->paginate(50);

        if ($request->ajax()) {
            $html = '';
            foreach ($users as $user) {
                $html .= view('admin.partials.user', ['user' => $user])->render();
            }

            return response()->json(['html' => $html, 'count' => $users->count()]);
        }

        return view('admin.users.search', compact('users'));
    }

    /**
     * Update the user's role.
     */
    public function changeRole(User $user, $role, Request $request): RedirectResponse
    {
        if (!in_array($role, ['user', 'ultra', 'admin'])) {
            return redirect()->route('admin.users.search')->with('error', __('message.profile.error.change_role'));
        }

        $this->profileService->changeUserRole($user, $role);

        $query = $request->query('search', '');
        $page = $request->query('page', 1);

        return redirect()->route('admin.users.search', [
            'search' => $query,
            'page' => $page
        ])->with('success', __('message.profile.success.change_role', ['user' => $user->username, 'role' => $role->name]));
    }

    /**
     * Display the followed threads.
     */
    public function thread(Request $request): View|JsonResponse
    {
        $user = Auth::user();

        $followedUserIds = $user->follows->pluck('id');

        // Query for the latest comments from the followed users with eager loading
        $comments = Comment::with([
            'content.user',
        ])
            ->withCount(['replies', 'likes'])
        ->whereHas('content.user', function ($query) use ($followedUserIds) {
            $query->whereIn('id', $followedUserIds);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $this->commentService->addAuthUserTags([$comments], $user);
        if ($request->ajax()) {
            return response()->json([
                'html' => view('posts.partials.comment-loop', ['comments' => $comments, 'depth' => -1])->render(),
                'next_page_url' => $comments->nextPageUrl()
            ]);
        }

        return view('profile.thread', compact('comments'));
    }

    public function checkUsername(Request $request)
    {
        $username = $request->query('username');

        $reservedUsernames = ReservedUsernamesHelper::getReservedUsernames();

        $exists = User::where('username', $username)->exists() || in_array(strtolower($username), $reservedUsernames);

        return response()->json(['exists' => $exists]);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->query('email');
        $exists = User::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }
}

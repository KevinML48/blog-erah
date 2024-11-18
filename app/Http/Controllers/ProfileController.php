<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CommentContent;
use App\Models\NotificationType;
use App\Models\Theme;
use App\Models\User;
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

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display the specified user's profile.
     */
    public function show($username): View
    {
        $user = $this->profileService->getUserProfile($username);
        $contents = $this->profileService->getUserComments($user, 5);

        return view('profile.show', [
            'user' => $user,
            'comments' => $contents,
        ]);
    }

    /**
     * Display the specified user's comment content list.
     */
    public function comments($username): View
    {
        $user = $this->profileService->getUserProfile($username);
        $contents = $this->profileService->getUserComments($user, 15);

        return view('profile.comments', [
            'user' => $user,
            'contents' => $contents,
        ]);
    }

    /**
     * Display the specified user's liked comments.
     */
    public function commentLikes($username): View
    {
        $user = $this->profileService->getUserProfile($username);
        $contents = $this->profileService->getUserLikedComments($user, 15);

        return view('profile.likes.comments', [
            'user' => $user,
            'contents' => $contents,
        ]);
    }

    /**
     * Display the specified user's liked posts.
     */
    public function postLikes($username): View
    {
        $user = $this->profileService->getUserProfile($username);
        $posts = $this->profileService->getUserLikedPosts($user, 15);

        return view('profile.likes.posts', [
            'user' => $user,
            'posts' => $posts,
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

        return Redirect::route('profile.edit')->with('success', 'Profil nis à jour');
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
            return redirect()->route('admin.users.search')->with('error', 'Vous ne pouvez pas supprimer votre compte ici.');
        }

        $this->profileService->deleteUserAccount($user);

        return redirect()->route('admin.users.search')->with('success', 'Utilisateur supprimé.');
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

        return redirect()->back()->with('success', 'Image de profil téléchargée');
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
                    ->orWhere('email', 'like', '%' . $search . '%');
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
            return redirect()->route('admin.users.search')->with('error', 'Rôle invalide');
        }

        $this->profileService->changeUserRole($user, $role);

        $query = $request->query('search', '');
        $page = $request->query('page', 1);

        return redirect()->route('admin.users.search', [
            'search' => $query,
            'page' => $page
        ])->with('success', 'Rôle changé avec succès');
    }

    /**
     * Display the followed threads.
     */
    public function thread(Request $request): View | JsonResponse
    {
        $user = Auth::user();
        $contents = CommentContent::whereIn('user_id', function ($query) use ($user) {
            $query->select('followed_id')
                ->from('follows')
                ->where('follower_id', $user->id);
        })
            ->with('user')
            ->latest()
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('posts.partials.content-loop', compact('contents'))->render(),
                'next_page_url' => $contents->nextPageUrl()
            ]);
        }

        return view('profile.thread', compact('contents'));
    }
}

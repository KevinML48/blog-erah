<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CommentContent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Display the specified user's profile.
     */
    public function show(Request $request, $username): View
    {
        $user = $request->user()->where('name', $username)->firstOrFail();
        $contents = $user->commentContents()->latest()->take(5)->get();

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
        $user = User::where('name', $username)->firstOrFail();
        $contents = $user->commentContents()->latest()->paginate(15);

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
        $user = User::where('name', $username)->firstOrFail();
        $contents = $user->likedComments()->latest()->paginate(15);

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
        $user = User::where('name', $username)->firstOrFail();
        $posts = $user->likedPosts()->latest()->paginate(15);

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
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

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

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

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

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        $user->profile_picture = $path;
        $user->save();

        return redirect()->back()->with('success', 'Image de profil téléchargée');
    }

    /**
     * Search and filter users.
     */
    public function search(Request $request): View|JsonResponse
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $sort = $request->input('sort', 'created_at'); // Default sort by created_at
        $direction = $request->input('direction', 'desc'); // Default direction is descending

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

        $user->role = $role;
        $user->save();

        $query = $request->query('search', '');
        $page = $request->query('page', 1);

        return redirect()->route('admin.users.search', [
            'search' => $query,
            'page' => $page
        ])->with('success', 'Rôle changé avec succès');
    }

    public function thread(Request $request): View | JsonResponse
    {
        $user = Auth::user();
        $contents = CommentContent::whereIn('user_id', $user->follows()->pluck('followed_id'))
            ->latest()
            ->paginate(5);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('posts.partials.content-loop', compact('contents'))->render(),
                'next_page_url' => $contents->nextPageUrl()
            ]);
        }

        return view('profile.thread', compact('contents'));
    }
}

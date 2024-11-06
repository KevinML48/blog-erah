<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
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

    public function comments(Request $request, $username): View
    {
        $user = User::where('name', $username)->firstOrFail();
        $contents = $user->commentContents()->latest()->paginate(15);

        return view('profile.comments', [
            'user' => $user,
            'contents' => $contents,
        ]);
    }

    public function commentLikes($username): View
    {
        $user = User::where('name', $username)->firstOrFail();
        $contents = $user->likedComments()->latest()->paginate(15);

        return view('profile.likes.comments', [
            'user' => $user,
            'contents' => $contents,
        ]);
    }

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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateProfilePicture(Request $request)
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

        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

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
}

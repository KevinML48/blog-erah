<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    public function getUserProfile(string $username): User
    {
        return User::where('username', $username)->firstOrFail();
    }

    public function getUserCommentContents(User $user, Authenticatable $authUser, int $limit = 15): LengthAwarePaginator
    {
        return $user->commentContents()
            ->with([
                'comment' => function ($query) {
                    $query->withCount('replies');
                },
                'comment.content' => function ($query) use ($authUser) {
                    $query->withCount('likes')
                        ->with('user')  // Load the user of the comment content
                        ->get()
                        ->each(function ($commentContent) use ($authUser) {
                            // Add a custom attribute to check if authUser follows the comment's user
                            $commentContent->user->is_followed_by_auth_user = $authUser->isFollowing($commentContent->user);
                        });
                },
                'comment.content.likes'
            ])
            ->latest()
            ->paginate($limit);
    }


    public function getUserLikedComments(User $user, int $limit = 15): LengthAwarePaginator
    {
        return $user->likedComments()->with(['comment', 'comment.content', 'comment.content.user', 'comment.content.likes'])
            ->latest()->paginate($limit);
    }

    public function getUserLikedPosts(User $user, int $limit = 15): LengthAwarePaginator
    {
        return $user->likedPosts()->with(['user', 'theme', 'likes'])
            ->latest()->paginate($limit);
    }

    public function updateUserProfilePicture(User $user, $filePath): void
    {
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->profile_picture = $filePath;
        $user->save();
    }

    public function deleteUserAccount(User $user): void
    {
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();
    }

    public function changeUserRole(User $user, string $role): void
    {
        $user->role = $role;
        $user->save();
    }
}

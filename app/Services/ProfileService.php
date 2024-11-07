<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    public function getUserProfile(string $username): User
    {
        return User::where('name', $username)->firstOrFail();
    }

    public function getUserComments(User $user, int $limit = 15): LengthAwarePaginator
    {
        return $user->commentContents()->latest()->paginate($limit);
    }

    public function getUserLikedComments(User $user, int $limit = 15): LengthAwarePaginator
    {
        return $user->likedComments()->latest()->paginate($limit);
    }

    public function getUserLikedPosts(User $user, int $limit = 15): LengthAwarePaginator
    {
        return $user->likedPosts()->latest()->paginate($limit);
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

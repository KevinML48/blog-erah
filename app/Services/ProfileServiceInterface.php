<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProfileServiceInterface
{
    public function getUserProfile(string $username): User;

    public function getUserComments(User $user, int $limit = 15): LengthAwarePaginator;

    public function getUserLikedComments(User $user, int $limit = 15): LengthAwarePaginator;

    public function getUserLikedPosts(User $user, int $limit = 15): LengthAwarePaginator;

    public function updateUserProfilePicture(User $user, $filePath): void;

    public function deleteUserAccount(User $user): void;

    public function changeUserRole(User $user, string $role): void;
}

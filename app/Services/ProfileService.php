<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\CommentContent;
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
                'comment.content.likes',
                'comment.content.comment.post' => function ($query) {
                    $query->select('id');
                }
            ])
            ->latest()
            ->paginate($limit);
    }

    public function getUserComments(User $user, int $limit = 15): LengthAwarePaginator
    {
        return $user->comments()->with([
            'content' => function ($query) {
                $query->withCount('likes')
                    ->with('user');
            },
            'content.user',
        ])
            ->withCount('replies')
            ->latest()
            ->paginate($limit);
    }


    public function getUserLikedComments(User $user, int $limit = 10): LengthAwarePaginator
    {
        // Retrieve the CommentContents liked by the user, ordered by the most recent like
        $likedContentIds = CommentContent::whereHas('likes', function ($query) use ($user) {
            $query->where('user_id', $user->id);  // Only likes by the specified user
        })
            ->orderByDesc(function ($query) {
                // Subquery to order by the most recent like on the content
                $query->select('created_at')
                    ->from('likes')
                    ->whereColumn('likeable_id', 'comments_content.id')
                    ->where('likeable_type', CommentContent::class)
                    ->orderByDesc('created_at')
                    ->limit(1);
            })
            ->pluck('comment_id');  // Get all associated comment_ids for these CommentContents

        // Retrieve Comments that are associated with the liked CommentContents, and order them by the latest like
        return Comment::whereIn('id', $likedContentIds)
        ->with([
            'content' => function ($query) {
                $query->withCount('likes');
            },
            'content.user',
        ])
            ->withCount([
                'replies',
            ])
            ->orderByRaw('FIELD(id, ' . implode(',', $likedContentIds->toArray()) . ')')  // Maintain the order based on likedContentIds
            ->paginate($limit);  // Paginate the results
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

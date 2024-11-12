<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $authenticatedUser = auth()->user();

        // Prevent a user from following themselves
        if ($authenticatedUser->id === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot follow yourself.'
            ]);
        }

        // If not already following, follow the user
        if (!$authenticatedUser->isFollowing($user)) {
            // Create a new Follow model instance
            Follow::create([
                'followed_id' => $user->id,  // The user being followed
                'follower_id' => $authenticatedUser->id,  // The authenticated user who is following
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'You are now following ' . $user->name,
                'followed' => true
            ]);
        }

        return response()->json([
            'status' => 'info',
            'message' => 'You are already following ' . $user->name,
            'followed' => true
        ]);
    }


    // Unfollow a user
    public function unfollow(User $user)
    {
        $authenticatedUser = auth()->user();

        // If already following, unfollow the user
        if ($authenticatedUser->isFollowing($user)) {
            // Find the follow record and delete it
            $follow = Follow::where('followed_id', $user->id)
                ->where('follower_id', $authenticatedUser->id)
                ->first();

            if ($follow) {
                $follow->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'You have unfollowed ' . $user->name,
                'followed' => false
            ]);
        }

        return response()->json([
            'status' => 'info',
            'message' => 'You are not following ' . $user->name,
            'followed' => false
        ]);
    }

}

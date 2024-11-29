<?php

return [
    'error' => [
        'server-size-limit' => 'The uploaded file exceeds the server size limit. Please upload a smaller file.',
    ],

    'follow' => [
        'info' => [
            'not-following' => "You are not following this user yet.",
        ],
        'error' => [
            'yourself' => "You cannot follow your own account.",
            'following' => "You are already following :user.",
        ],
        'success' => [
            'follow' => "You are now following :user.",
            'unfollow' => "You have unfollowed :user.",
        ],
    ],

    'posts' => [
        'success' => [
            'created' => 'Post created successfully.',
            'updated' => 'Post updated successfully.',
            'deleted' => 'Post deleted successfully.',
        ],
        'error' => [
            'not-found' => 'The requested post does not exist.',
        ],
        'info' => [
            'no-posts' => 'No posts found.',
        ],
    ],

    'profile' => [
        'success' => [
            'update' => 'Profile updated successfully.',
            'profile_picture_update' => 'Profile picture updated successfully.',
            'deleted' => 'User deleted successfully.',
            'change_role' => 'Role of :user changed to :role.'
        ],
        'error' => [
            'change_role' => 'Invalid role.',
            'cannot_delete_own_account' => 'Use the profile page to delete your account.',
        ],
    ],

    'theme' => [
        'success' => [
            'create' => 'Theme created successfully.',
            'update' => 'Theme updated successfully.',
            'delete' => 'Theme deleted successfully.',
        ],
    ],

    'user-notification-preference' => [
        'success' => [
            'update' => 'Preferences updated.',
            'mute-comment' => 'Comment successfully muted.',
            'unmute-comment' => 'Comment successfully unmuted.',
        ],
    ],
];

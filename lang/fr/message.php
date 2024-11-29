<?php

return [
    'error' => [
        'server-size-limit' => 'Le fichier téléchargé dépasse la limite de taille du serveur. Veuillez télécharger un fichier plus petit.',
    ],

    'follow' => [
        'info' => [
            'not-following' => "Vous ne suivez pas encore cet utilisateur.",
        ],
        'error' => [
            'yourself' => "Vous ne pouvez pas suivre votre propre compte.",
            'following' => "Vous suivez déjà :user.",
        ],
        'success' => [
            'follow' => "Vous suivez maintenant :user.",
            'unfollow' => "Vous avez arrêté de suivre :user.",
        ],
    ],

    'posts' => [
        'success' => [
            'created' => 'Post créé avec succès.',
            'updated' => 'Post mis à jour avec succès.',
            'deleted' => 'Post supprimé avec succès.',
        ],
        'error' => [
            'not-found' => 'Le post demandé n\'existe pas.',
        ],
        'info' => [
            'no-posts' => 'Aucun post trouvé.',
        ],
    ],

    'profile' => [
        'success' => [
            'update' => 'Profil mis à jour avec succès.',
            'profile_picture_update' => 'Image de profil mise à jour avec succès.',
            'deleted' => 'Utilisateur supprimé avec succès.',
            'change_role' => 'Rôle de :user changé à :role.'
        ],
        'error' => [
            'change_role' => 'Rôle invalide',
            'cannot_delete_own_account' => 'Utilisez la page de profil pour supprimer votre compte.',
        ],
    ],

    'theme' => [
        'success' => [
            'create' => 'Thème créé avec succès',
            'update' => 'Thème mis à jour avec succès',
            'delete' => 'Thème supprimé avec succès',
        ],
    ],

    'user-notification-preference' => [
        'success' => [
            'update' => 'Préférences mises à jour',
            'mute-comment' => 'Commentaire ignoré avec succès',
            'unmute-comment' => 'Commentaire rétabli avec succès',
        ],
    ],
];

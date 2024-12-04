<?php

namespace App\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\DatabaseNotification;

interface NotificationStrategy
{
    public function handleCreation(): void;

    public function handleDeletion(): void;

    public function processNotification(DatabaseNotification $notification, Authenticatable $authUser = null);
}

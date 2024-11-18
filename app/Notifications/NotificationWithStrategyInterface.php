<?php

namespace App\Notifications;

use App\Contracts\NotificationStrategy;

interface NotificationWithStrategyInterface
{
    public function getNotificationStrategy(): NotificationStrategy;
}

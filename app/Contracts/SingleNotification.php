<?php

namespace App\Contracts;

interface SingleNotification extends NotifiableEntityInterface
{
    public function getNotificationStrategy(): NotificationStrategy;
}

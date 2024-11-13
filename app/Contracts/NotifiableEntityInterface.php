<?php

namespace App\Contracts;

interface NotifiableEntityInterface
{
    public function getNotificationStrategy(): NotificationStrategy;
}

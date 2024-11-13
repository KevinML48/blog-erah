<?php

namespace App\Contracts;

interface NotificationStrategy
{
    public function handleCreation(): void;
}

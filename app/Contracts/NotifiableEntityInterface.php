<?php

namespace App\Contracts;

interface NotifiableEntityInterface
{
    /**
     * Get the notification class for this model.
     *
     * @return string
     */
    public function getNotificationClass(): string;

    /**
     * Get the notification type for this model (e.g., 'comment_like' or 'follow').
     *
     * @return string
     */
    public function getNotificationType(): ?string;

    /**
     * Get the context ID for this model (e.g., comment_id or user_id).
     *
     * @return int
     */
    public function getContextId();

    public function getContextType(): ?string;

    public function targetUser();
}

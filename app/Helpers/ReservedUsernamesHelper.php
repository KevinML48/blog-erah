<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class ReservedUsernamesHelper
{

    /**
     * Get reserved usernames based on registered routes.
     *
     * @return array
     */
    public static function getReservedUsernames(): array
    {
        $reservedUsernames = [];

        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            if (preg_match('#^profil/([^/]+)#', $route->uri, $matches)) {
                $reservedUsernames[] = strtolower($matches[1]);
            }
        }

        return $reservedUsernames;
    }

}

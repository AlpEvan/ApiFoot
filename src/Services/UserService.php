<?php

declare(strict_types=1);

namespace EvanAlpst\ApiFoot\Services;

use EvanAlpst\ApiFoot\Models\User;

class UserService
{
    /**
     * Get the current user
     *
     * @return User|null
     */
    public static function current(): ?User
    {
        static $current = null;

        if (!$current) {
            $email = $_SESSION['user'] ?? null;

            if ($email !== null) {
                $current = $email ? User::findByEmail($email) : null;
            }
        }

        return $current;
    }

    /**
     * Return whether the user is connected or not
     *
     * @return boolean
     */
    public static function isConnected(): bool
    {
        return static::current() instanceof User;
    }

    /**
     * Store user in session
     *
     * @param User $user
     * @return void
     */
    public static function connect(User $user): void
    {
        // Put in session
        $_SESSION['user'] = $user->email;
        session_regenerate_id(true);
    }
}
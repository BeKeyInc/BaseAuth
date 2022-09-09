<?php

declare(strict_types=1);

namespace BeKey\Auth\Helpers;

use BeKey\Auth\Contract\IdentityInterface;

class AuthHelper
{
    private static ?IdentityInterface $user = null;

    /**
     * @return IdentityInterface|null
     */
    public static function getUser(): ?IdentityInterface
    {
        return self::$user;
    }

    /**
     * @param IdentityInterface|null $user
     */
    public static function setUser(?IdentityInterface $user): void
    {
        self::$user = $user;
    }
}

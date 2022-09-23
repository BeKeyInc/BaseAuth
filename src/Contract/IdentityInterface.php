<?php

namespace BeKey\Auth\Contract;

interface IdentityInterface
{
    /**
     * @param string $password
     * @return bool
     */
    public function isValidPassword(string $password): bool;

    /**
     * @param null|string $password
     */
    public function generatePasswordHash(?string $password): void;

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string|null
     */
    public function getAuthIdentifierName(): ?string;

    /**
     * Get the unique identifier for the user.
     *
     * @return int|string|null
     */
    public function getAuthIdentifier(): int|string|null;

    /**
     * @return string|null
     */
    public function getRole(): ?string;

    /**
     * get hash string for unique active token at a time
     * @return string|null
     */
    public function getHash(): ?string;
}

<?php

namespace BeKey\Auth\Contract;

use BeKey\Auth\Dto\JwtObject;
use BeKey\Auth\Dto\JwtTokenDto;

/**
 * Interface JwtService
 * @package App\Contract\Services
 */
interface JwtServiceInterface
{
    /**
     * @param IdentityInterface $user
     * @param string|null $hash
     * @param string $sub
     * @param int|null $accessLifetime
     * @param bool $needRefreshToken
     * @param array $payload
     * @return JwtTokenDto
     */
    public function generateTokenToUser(
        IdentityInterface $user,
        ?string $hash,
        string $sub,
        ?int $accessLifetime = null,
        bool $needRefreshToken = true,
        array $payload = []
    ): JwtTokenDto;

    /**
     * @param string $token
     * @return JwtObject
     */
    public function decode(string $token): JwtObject;

    /**
     * @param string $token
     * @return JwtObject
     */
    public function decodeTokenWithoutExpiredCheck(string $token): JwtObject;
}

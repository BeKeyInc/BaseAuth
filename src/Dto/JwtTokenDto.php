<?php

declare(strict_types=1);

namespace BeKey\Auth\Dto;

class JwtTokenDto
{
    /**
     * JwtTokenDto constructor.
     * @param string $tokenType
     * @param int $expiresIn
     * @param string $accessToken
     * @param string|null $refreshToken
     */
    public function __construct(
        readonly public string $tokenType,
        readonly public int $expiresIn,
        readonly public string $accessToken,
        readonly public ?string $refreshToken
    ) {
    }
}

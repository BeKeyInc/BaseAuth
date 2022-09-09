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
     * @param string $refreshToken
     */
    public function __construct(
        public string $tokenType,
        public int $expiresIn,
        public string $accessToken,
        public string $refreshToken
    ) {
    }
}

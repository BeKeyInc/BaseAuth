<?php

namespace BeKey\Auth\Contract;

use BeKey\Auth\Dto\JwtTokenDto;
use BeKey\Auth\Enum\JwtSubEnum;

interface AuthServiceInterface
{
    /**
     * @param string $type
     * @param string $identifier
     * @param string|null $password
     * @return JwtTokenDto
     */
    public function login(string $type, string $identifier, ?string $password): JwtTokenDto;

    /**
     * @param IdentityInterface $identity
     * @return JwtTokenDto
     */
    public function loginUser(IdentityInterface $identity): JwtTokenDto;

    /**
     * @param string $token
     * @return JwtTokenDto
     */
    public function refreshToken(string $token): JwtTokenDto;

    /**
     * @param string $token
     * @param string $sub
     */
    public function authByToken(string $token, string $sub = JwtSubEnum::SUB_BASE_AUTH): void;

    public function logout(): void;

    /**
     * @param IdentityInterface|null $identity
     * @return void
     */
    public function auth(?IdentityInterface $identity): void;

    /**
     * @return IdentityInterface|null
     */
    public function getAuth(): ?IdentityInterface;
}

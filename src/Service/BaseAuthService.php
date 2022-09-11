<?php

declare(strict_types=1);

namespace BeKey\Auth\Service;

use BeKey\Auth\Contract\AuthServiceInterface;
use BeKey\Auth\Contract\IdentityInterface;
use BeKey\Auth\Contract\JwtServiceInterface;
use BeKey\Auth\Dto\JwtTokenDto;
use BeKey\Auth\Enum\AuthTypeEnum;
use BeKey\Auth\Enum\ExceptionCodesEnum;
use BeKey\Auth\Enum\JwtSubEnum;
use BeKey\Auth\Exception\AuthException;
use BeKey\Auth\Helpers\AuthHelper;

abstract class BaseAuthService implements AuthServiceInterface
{
    protected string $pattern = '/^Bearer\s+(.*?)$/';

    abstract protected function getIdentityByIdentifier(?string $identifier, ?string $iss): IdentityInterface;
    abstract protected function codeAuth(string $identifier): ?IdentityInterface;
    abstract protected function prepareIdentityAfterLogin(IdentityInterface $identity): IdentityInterface;
    abstract protected function changeHash(IdentityInterface $identity): void;

    /**
     * BaseAuthService constructor.
     * @param JwtServiceInterface $jwtService
     */
    public function __construct(
        private JwtServiceInterface $jwtService
    ) {
    }

    /**
     * @param string $type
     * @param string $identifier
     * @param string|null $password
     * @return JwtTokenDto
     * @throws AuthException
     */
    public function login(string $type, string $identifier, ?string $password): JwtTokenDto
    {
        $identity = match ($type) {
            AuthTypeEnum::PASSWORD => $this->passwordAuth($identifier, $password),
            AuthTypeEnum::CODE => $this->codeAuth($identifier),
            default => throw new AuthException(ExceptionCodesEnum::NOT_VALID_TYPE, 'Wrong credentials')
        };

        if ($identity === null) {
            throw new AuthException(ExceptionCodesEnum::NOT_FIND_VALID_IDENTITY, 'Wrong credentials');
        }

        $identity = $this->prepareIdentityAfterLogin($identity);
        $this->auth($identity);

        return $this->jwtService->generateTokenToUser($identity, $identity->getHash(), JwtSubEnum::SUB_BASE_AUTH);
    }

    /**
     * @param string $token
     * @return JwtTokenDto
     * @throws AuthException
     */
    public function refreshToken(string $token): JwtTokenDto
    {
        $this->authByToken($token, JwtSubEnum::SUB_REFRESH_TOKEN_BASE_AUTH);
        $identity = $this->getAuth();

        return $this->jwtService->generateTokenToUser($identity, $identity->getHash(), JwtSubEnum::SUB_BASE_AUTH);
    }

    /**
     * @param string $token
     * @param string $sub
     * @throws AuthException
     */
    public function authByToken(string $token, string $sub = JwtSubEnum::SUB_BASE_AUTH): void
    {
        if (!$token) {
            throw new AuthException(ExceptionCodesEnum::AUTH_TOKEN_NOT_SET, 'Wrong auth token');
        }
        if (!preg_match($this->pattern, $token, $matches)) {
            throw new AuthException(ExceptionCodesEnum::AUTH_TOKEN_NOT_VALID, 'Wrong auth token');
        }

        $decodeToken = $this->jwtService->decode($matches[1]);

        if (!$decodeToken) {
            throw new AuthException(ExceptionCodesEnum::AUTH_TOKEN_ERROR_DECODE, 'Wrong auth token');
        }
        if (empty($decodeToken->iss)) {
            throw new AuthException(ExceptionCodesEnum::AUTH_TOKEN_ERROR_ISS, 'Wrong auth token');
        }
        if ($decodeToken->sub !== $sub) {
            throw new AuthException(ExceptionCodesEnum::AUTH_TOKEN_ERROR_SUB, 'Wrong auth token');
        }

        $identity = $this->getIdentityByIdentifier(null, $decodeToken->iss);

        if ($decodeToken->hash !== $identity->getHash()) {
            throw new AuthException(ExceptionCodesEnum::AUTH_TOKEN_ERROR_HASH, 'Wrong auth token');
        }

        $this->auth($identity);
    }

    public function logout(): void
    {
        $identity = $this->getAuth();

        if ($identity === null) {
            throw new AuthException(ExceptionCodesEnum::NOT_LOGIN_USER, 'Wrong user');
        }

        $this->changeHash($identity);
    }

    public function auth(?IdentityInterface $identity): void
    {
        AuthHelper::setUser($identity);
    }

    public function getAuth(): ?IdentityInterface
    {
        return AuthHelper::getUser();
    }

    /**
     * @param string $identifier
     * @param string $password
     * @return IdentityInterface
     * @throws AuthException
     */
    protected function passwordAuth(string $identifier, string $password): IdentityInterface
    {
        $identity = $this->getIdentityByIdentifier($identifier, null);

        if ($identity->isValidPassword($password) === false) {
            throw new AuthException(ExceptionCodesEnum::NOT_VALID_IDENTITY_PASSWORD, 'Wrong credentials');
        }

        return $identity;
    }
}

<?php

declare(strict_types=1);

namespace BeKey\Auth\Service;

use BeKey\Auth\Contract\IdentityInterface;
use BeKey\Auth\Contract\JwtServiceInterface;
use BeKey\Auth\Dto\JwtObject;
use BeKey\Auth\Dto\JwtTokenDto;
use BeKey\Auth\Enum\ExceptionCodesEnum;
use BeKey\Auth\Enum\JwtSubEnum;
use BeKey\Auth\Exception\AuthException;
use Carbon\Carbon;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class JwtService
 * @package App\Servicess
 */
class JwtService implements JwtServiceInterface
{
    protected string $key;
    protected int $accessTokenLifetime;
    protected int $refreshTokenLifetime;

    /**
     * JwtService constructor.
     */
    public function __construct()
    {
        $this->key = env('APP_KEY');
        $this->accessTokenLifetime = (int)env('ACCESS_TOKEN_LIFETIME_IN_MINUTE', 60);
        $this->refreshTokenLifetime = (int)env('REFRESH_TOKEN_LIFETIME_IN_MINUTE', 240);
    }

    /**
     * @param string $token
     * @return JwtObject
     */
    public function decode(string $token): JwtObject
    {
        try {
            return $this->getJwtObject(
                JWT::decode($token, new Key($this->key, 'HS256'))
            );
        } catch (ExpiredException $exception) {
            throw new AuthException(ExceptionCodesEnum::JWT_TOKEN_EXPIRED, 'Token expired');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * get decoded data Without checked Expired
     *
     * @param string $token
     * @return JwtObject
     * @throws \Exception
     */
    public function decodeTokenWithoutExpiredCheck(string $token): JwtObject
    {
        $oldLeeway = JWT::$leeway;
        JWT::$leeway = is_null(JWT::$timestamp) ? time() : JWT::$timestamp;

        $data = JWT::decode($token, new Key($this->key, 'HS256'));

        JWT::$leeway = $oldLeeway;

        return $this->getJwtObject($data);
    }

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
    ): JwtTokenDto {
        $now = Carbon::now();
        $exp = $now->clone()->addMinutes($accessLifetime ?? $this->accessTokenLifetime)->timestamp;

        $tokenData = array(
            "iss" => $user->getAuthIdentifier(),
            "role" => $user->getRole(),
            "iat" => $now->timestamp,
            "sub" => $sub,
            "aud" => $user->getAuthIdentifierName(),
            "hash" => $hash,
            "exp" => $exp,
        );

        if ($payload) {
            $tokenData = array_merge($payload, $tokenData);
        }

        $accessToken = JWT::encode($tokenData, $this->key, 'HS256');

        return new JwtTokenDto(
            'Bearer',
            $exp,
            $accessToken,
            $needRefreshToken ? $this->getRefreshToken($tokenData, $now) : null
        );
    }

    protected function getJwtObject(array|\stdClass $params): JwtObject
    {
        return new JwtObject($params);
    }

    protected function getRefreshToken(array $tokenData, Carbon $now): string
    {
        $tokenData['exp'] = $now->clone()->addMinutes($this->refreshTokenLifetime)->timestamp;
        $tokenData['sub'] = JwtSubEnum::SUB_REFRESH_TOKEN_BASE_AUTH;

        return JWT::encode($tokenData, $this->key, 'HS256');
    }
}

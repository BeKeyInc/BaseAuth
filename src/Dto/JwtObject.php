<?php

declare(strict_types=1);

namespace BeKey\Auth\Dto;

class JwtObject
{
    public ?string $iss = null;
    public ?int $iat = null;
    public ?string $sub = null;
    public ?string $aud = null;
    public ?string $hash = null;
    public ?int $exp = null;
    public ?string $role = null;

    /**
     * JwtObject constructor.
     * @param array|\stdClass $data
     */
    public function __construct(array|\stdClass $params)
    {
        if ($params) {
            foreach ($params as $key => $param) {
                if (property_exists(static::class, $key)) {
                    $this->$key = $param;
                }
            }
        }
    }
}

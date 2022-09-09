<?php

namespace BeKey\Auth\Enum;

use App\Base\Enums\BaseEnum;

class JwtSubEnum extends BaseEnum
{
    public const SUB_BASE_AUTH = 'baseAuth';
    public const SUB_REFRESH_TOKEN_BASE_AUTH = 'refreshTokenBaseAuth';
}

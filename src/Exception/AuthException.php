<?php

namespace BeKey\Contract\Exception;


class AuthException extends \Exception
{
    public function getType(): string
    {
        return 'AuthException';
    }

}

<?php

namespace BeKey\Auth\Exception;

class AuthException extends \Exception
{
    public function __construct(
        public int $errorCode,
        string $message
    ) {
        parent::__construct($message, 401);
    }
}

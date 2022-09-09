<?php

namespace BeKey\Auth\Enum;

class ExceptionCodesEnum
{
    public const NOT_VALID_TYPE = 10;
    public const NOT_FIND_VALID_IDENTITY = 11;
    public const NOT_VALID_IDENTITY_PASSWORD = 12;
    public const NOT_LOGIN_USER = 13;

    public const JWT_TOKEN_EXPIRED = 20;

    public const AUTH_TOKEN_NOT_SET = 30;
    public const AUTH_TOKEN_NOT_VALID = 31;
    public const AUTH_TOKEN_ERROR_DECODE = 32;
    public const AUTH_TOKEN_ERROR_ISS = 33;
    public const AUTH_TOKEN_ERROR_SUB = 34;
    public const AUTH_TOKEN_ERROR_HASH = 35;
}

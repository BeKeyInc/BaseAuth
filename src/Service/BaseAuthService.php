<?php

namespace BeKey\Service;


use BeKey\Contract\AuthServiceInterface;
use BeKey\Contract\IdentityInterface;

abstract class BaseAuthService implements AuthServiceInterface
{
    /**
     * @param string $type
     * @param string $identifier
     * @param string|null $password
     * @return void
     */
    public function login(string $type, string $identifier, ?string $password): void
    {
        /** @var ?IdentityInterface $identity */
        $identity = null;

        switch ($type) {
            case self::TYPE_PASSWORD_LOGIN:
            {
                $identity = $this->passwordAuth($identifier, $password);

                break;
            }
            case self::TYPE_CODE_LOGIN:
            {
                $identity = $this->codeAuth($identifier);

                break;
            }
        }

        $this->auth(identity: $identity);
    }
}

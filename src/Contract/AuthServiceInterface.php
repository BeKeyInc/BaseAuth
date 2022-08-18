<?php

namespace BeKey\Contract;



interface AuthServiceInterface
{
    const TYPE_PASSWORD_LOGIN = 'password';
    const TYPE_CODE_LOGIN = 'code';

    /**
     * @param string $identifier
     * @param string $password
     * @return IdentityInterface
     */
    public function passwordAuth(string $identifier, string $password): ?IdentityInterface;

    /**
     * @param string $identifier
     * @return IdentityInterface
     */
    public function codeAuth(string $identifier): ?IdentityInterface;

    /**
     * @param IdentityInterface|null $identity
     * @return void
     */
    public function auth(?IdentityInterface $identity): void;

    /**
     * @param string $type
     * @param string $identifier
     * @param ?string $password
     * @return void
     */
    public function login(string $type, string $identifier, ?string $password): void;
}

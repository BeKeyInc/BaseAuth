<p align="center"><a href="https://bekey.io/" target="_blank"><img src="https://bekey.io/static/images/logo/bekey-logo.jpeg" width="200"></a></p>

# Our website

<p><a href="https://bekey.io/" target="_blank">https://bekey.io/</a></p>

# BaseAuth
Base auth module

BaseAuthService needs to be extends and implement methods:

    public function auth(?IdentityInterface $identity): void;
    
    public function passwordAuth(string $identifier, string $password): ?IdentityInterface;

    public function codeAuth(string $identifier): ?IdentityInterface;
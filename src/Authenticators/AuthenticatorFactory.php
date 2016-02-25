<?php
namespace Caffeinated\Github\Authenticators;

use InvalidArgumentException;

class AuthenticatorFactory
{
    /**
     * Make a new authenticator instance.
     *
     * @param  string  $method
     * @return Caffeinated\Github\Authenticators\AuthenticatorInterface
     */
    public function make($method)
    {
        switch ($method) {
            case 'password':
                return new PasswordAuthenticator();
            case 'token':
                return new TokenAuthenticator();
        }

        throw new InvalidArgumentException(sprintf('Unsupported authentication method: %s', $method));
    }
}

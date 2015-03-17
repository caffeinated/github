<?php
namespace Caffeinated\Github\Authenticators;

use InvalidArgumentException;

class AuthenticatorFactory
{
    public function make($method)
    {
        switch ($method) {
            case 'application':
                return new ApplicationAuthenticator();
                break;
            case 'password':
                return new PasswordAuthenticator();
                break;
            case 'token':
                return new TokenAuthenticator();
                break;
        }

        throw new InvalidArgumentException(sprintf('Unsupported authentication method: %s', $method));
    }
}

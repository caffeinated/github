<?php
namespace Caffeinated\Github\Authenticators;

use InvalidArgumentException;

class AuthenticatorFactory
{
    public function make($method)
    {
        switch ($method) {
            case 'token':
                return new TokenAuthenticator();
                break;
        }

        throw new InvalidArgumentException(sprintf('Unsupported authentication method: %s', $method));
    }
}

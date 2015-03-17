<?php
namespace Caffeinated\Github\HttpClient\Listeners;

use Caffeinated\Github\Client;
use Guzzle\Common\Event;
use RuntimeException;

class AuthListener
{
    private $tokenOrLogin;

    private $password;

    private $method;

    public function __construct($tokenOrLogin, $password = null, $method)
    {
        $this->tokenOrLogin = $tokenOrLogin;
        $this->password     = $password;
        $this->method       = $method;
    }

    public function onRequestBeforeSend(Event $event)
    {
        if (is_null($this->method)) {
            return;
        }

        switch ($this->method) {
            default:
                throw new RuntimeException(sprintf('%s is not yet implemented.', $this->method));
                break;
        }
    }
}

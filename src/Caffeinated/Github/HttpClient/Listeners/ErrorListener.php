<?php
namespace Caffeinated\Github\HttpClient\Listeners;

use Caffeinated\Github\Exceptions\TwoFactorAuthenticationRequiredException;
use Guzzle\Common\Event;
use Guzzle\Http\Message\Response;

class ErrorListener
{
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function onRequestError(Event $event)
    {
        $request  = $event['request'];
        $response = $request->getResponse();
    }
}

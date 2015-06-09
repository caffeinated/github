<?php
namespace Caffeinated\Github\HttpClient\Listeners;

use RuntimeException;
use Caffeinated\Github\Client;
use GuzzleHttp\Event\EmitterInterface;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Event\BeforeEvent;

class AuthListener implements SubscriberInterface
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

    public function getEvents()
    {
        return [
            'before' => ['onBefore']
        ];
    }

    public function onBefore(BeforeEvent $event, $name)
    {
        if (is_null($this->method)) {
            return;
        }

        $request = $event->getRequest();

        switch ($this->method) {
            case Client::AUTH_HTTP_PASSWORD:
                $request->setHeader('Authorization', sprintf('Basic %s', base64_encode($this->tokenOrLogin.':'.$this->password)));
                break;
            case Client::AUTH_HTTP_TOKEN:
                $request->setHeader('Authorization', sprintf('token %s', $this->tokenOrLogin));
                break;
            case Client::AUTH_URL_CLIENT_ID:
                $url = $request->getUrl();

                $parameters = [
                    'client_id'     => $this->tokenOrLogin,
                    'client_secret' => $this->password
                ];

                $url .= (false === strpos($url, '?') ? '?' : '&');
                $url .= utf8_encode(http_build_query($parameters, '', '&'));

                $request->setUrl($url);
                break;
            case Client::AUTH_URL_TOKEN:
                $url  = $request->getUrl();
                $url .= (false === strpos($url, '?') ? '?' : '&');
                $url .= utf8_encode(http_build_query(['access_token' => $this->tokenOrLogin], '', '&'));

                $request->getUrl($url);
                break;
            default:
                throw new RuntimeException(sprintf('%s is not yet implemented.', $this->method));
                break;
        }
    }
}

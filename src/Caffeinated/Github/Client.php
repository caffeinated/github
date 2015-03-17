<?php
namespace Caffeinated\Github;

use BadMethodCallException;
use InvalidArgumentException;
use Caffeinated\Github\HttpClient\HttpClient;

class Client
{
    const AUTH_URL_TOKEN = 'url_token';

    const AUTH_URL_CLIENT_ID = 'url_client_id';

    const AUTH_HTTP_PASSWORD = 'http_password';

    const AUTH_HTTP_TOKEN = 'http_token';

    private $options = array(
        'base_url'    => 'https://api.github.com/',
        'user_agent'  => 'caffeinated-github',
        'timeout'     => 10,
        'api_limit'   => 5000,
        'api_version' => 'v3',
        'cache_dir'   => null
    );

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient;
    }

    public function api($name)
    {
        switch($name) {
            case 'repo':
            case 'repos':
            case 'repository':
            case 'repositories':
                $api = new Api\Repo($this);
                break;

            case 'user':
                $api = new Api\User($this);
                break;

            default:
                throw new InvalidArgumentException(sprintf('Undefined API instance called: "%s"', $name));
        }

        return $api;
    }

    public function authenticate($tokenOrLogin, $password = null, $authMethod = null)
    {
        if (is_null($password) and is_null($authMethod)) {
            throw new InvalidArgumentException('You must specify an authentication method.');
        }

        if (is_null($authMethod) and in_array($password, [self::AUTH_URL_TOKEN, self::AUTH_URL_CLIENT_ID, self::AUTH_HTTP_PASSWORD, self::AUTH_HTTP_TOKEN])) {
            $authMethod = $password;
            $password   = null;
        }

        if (is_null($authMethod)) {
            $authMethod = self::AUTH_HTTP_PASSWORD;
        }

        $this->getHttpClient()->authenticate($tokenOrLogin, $password, $authMethod);
    }

    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient($this->options);
        }

        return $this->httpClient;
    }

    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function clearHeaders()
    {
        $this->getHttpClient()->clearHeaders();
    }

    public function setHeaders()
    {
        $this->getHttpClient()->setHeaders($headers);
    }

    public function getOption($key)
    {
        if (! array_key_exists($key, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $key));
        }

        return $this->options[$key];
    }

    public function setOption($key, $value)
    {
        if (! array_key_exists($key, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $key));
        }

        $supportedAPIVersions = $this->getSupportedAPIVersions();

        if ('api_version' == $key and ! in_array($value, $supportedAPIVersions)) {
            throw new InvalidArgumentException(sprintf('Invalid API version ("%s"), valid options are: %s', $key, implode(', ', $supportedAPIVersions)));
        }

        $this->options[$key] = $value;
    }

    public function getSupportedAPIVersions()
    {
        return ['v3'];
    }

    public function __call($name, $args)
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }
}

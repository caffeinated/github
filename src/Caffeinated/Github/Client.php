<?php
namespace Caffeinated\Github;

use BadMethodCallException;
use InvalidArgumentException;
use Caffeinated\Github\HttpClient\HttpClient;

class Client
{
    /**
     * Indicated the default, but deprecated login with username and token
     * in the URL.
     */
    const AUTH_URL_TOKEN = 'url_token';

    /**
     * Indicates the usage of anauthenticated rate limited requests for the
     * given client_id + client_secret.
     */
    const AUTH_URL_CLIENT_ID = 'url_client_id';

    /**
     * Indicates the new favored login method with username and password
     * via HTTP Authentication.
     */
    const AUTH_HTTP_PASSWORD = 'http_password';

    /**
     * Indicates the new login method with username and token via HTTP
     * Authentication.
     */
    const AUTH_HTTP_TOKEN = 'http_token';

    /**
     * @var array
     */
    private $options = array(
        'base_url'    => 'https://api.github.com/',
        'user_agent'  => 'caffeinated-github',
        'timeout'     => 10,
        'api_limit'   => 5000,
        'api_version' => 'v3',
        'cache_dir'   => null
    );

    /**
     * @var Caffeinated\Github\HttpClient\HttpClient
     */
    private $httpClient;

    /**
     * Create a new instance of Client.
     *
     * @param  Caffeinated\Github\HttpClient\HttpClient  $httpClient
     */
    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Delegates API requests to the correct handler.
     *
     * @param  string  $name
     * @throws InvalidArgumentException
     * @return Api
     */
    public function api($name)
    {
        switch($name) {
            case 'organization':
            case 'organizations':
                $api = new Api\Organization($this);
                break;

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

    /**
     * Authenticate user for all requests.
     *
     * @param  string       $tokenOrLogin
     * @param  null|string  $password
     * @param  null|string  $authMethod
     * @throws InvalidArgumentException
     * @return null
     */
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

    /**
     * Get the HttpClient instance.
     *
     * @return Caffeinated\Github\HttpClient\HttpClient
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient($this->options);
        }

        return $this->httpClient;
    }

    /**
     * Set the httpClient instance.
     *
     * @param  HttpClientInterface  $httpClient
     * @return null
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Clear HttpClient headers.
     *
     * @return null
     */
    public function clearHeaders()
    {
        $this->getHttpClient()->clearHeaders();
    }

    /**
     * Set HttpClient headers.
     *
     * @return null
     */
    public function setHeaders()
    {
        $this->getHttpClient()->setHeaders($headers);
    }

    /**
     * Get an option value.
     *
     * @param  string  $key
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function getOption($key)
    {
        if (! array_key_exists($key, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $key));
        }

        return $this->options[$key];
    }

    /**
     * Set an option's value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @throws InvalidArgumentException
     * @return null
     */
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

    /**
     * Returns an array of supported API versions.
     *
     * @return array
     */
    public function getSupportedAPIVersions()
    {
        return ['v3'];
    }

    /**
     * Magic __call method against the API.
     *
     * @param  string  $name
     * @param  mixed   $args
     * @throws BadMethodCallException
     * @return Api
     */
    public function __call($name, $args)
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }
}

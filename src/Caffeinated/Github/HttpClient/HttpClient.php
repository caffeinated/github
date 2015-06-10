<?php
namespace Caffeinated\Github\HttpClient;

use ErrorException;
use RuntimeException;
use Caffeinated\Github\HttpClient\Interfaces\HttpClientInterface;
use Caffeinated\Github\HttpClient\Listeners\AuthListener;
use Caffeinated\Github\HttpClient\Listeners\ErrorListener;
use GuzzleHttp\Client as GuzzleClient;

class HttpClient implements HttpClientInterface
{
    /**
     * @var array
     */
    protected $options = [
        'base_url'    => 'https://api.github.com/',
        'user_agent'  => 'caffeinated-github',
        'api_version' => 'v3',
        'cache_dir'   => null
        'guzzle'      => [
            'timeout' => 10
        ],
    ];

    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @var array
     */
    private $lastResponse;

    /**
     * @var array
     */
    private $lastRequest;

    /**
     * Create a new instance of HttpClient.
     *
     * @param  array            $options
     * @param  ClientInterface  $client
     */
    public function __construct(array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);

        $client       = $client ?: new GuzzleClient(['base_url' => $this->base_url, 'defaults' => $this->options['guzzle']]);
        $this->client = $client;

        $this->client->setDefaultOption('verify', false);

        $this->clearHeaders();
    }

    /**
     * Set an option value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @throws InvalidArgumentException
     * @return null
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Set HTTP headers.
     *
     * @param  array  $headers
     * @return null
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Clear used HTTP headers.
     *
     * @return null
     */
    public function clearHeaders()
    {
        $this->headers = array(
            'Accept'     => sprintf('application/vnd.github.%s+json', $this->options['api_version']),
            'User-Agent' => sprintf('%s', $this->options['user_agent']),
        );
    }

    /**
     * Add a new client listener.
     *
     * @param  string  $eventName
     * @param  array   $listener
     * @return null
     */
    public function addListener($eventName, $listener)
    {
        $this->client->getEmitter()->attach($listener[0]);
    }

    /**
     * Add a new client subscriber.
     *
     * @param  EventSubscriberInterface  $subscriber
     * @return null
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->client->getEmitter()->attach($subscriber);
    }

    /**
     * Send a GET request.
     *
     * @param  string  $path
     * @param  array   $parameters
     * @param  array   $headers
     * @return Response
     */
    public function get($path, array $parameters = array(), array $headers = array())
    {
        return $this->request($path, null, 'GET', $headers, array('query' => $parameters));
    }

    /**
     * Send a POST request.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @return Response
     */
    public function post($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'POST', $headers);
    }

    /**
     * Send a PATCH request.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @return Response
     */
    public function patch($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'PATCH', $headers);
    }

    /**
     * Send a DELETE request.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @return Response
     */
    public function delete($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'DELETE', $headers);
    }

    /**
     * Send a PUT request.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @return Response
     */
    public function put($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'PUT', $headers);
    }

    /**
     * Send a request to the server, and receive a response.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  string  $httpMethod
     * @param  array   $headers
     * @return Response
     */
    public function request($path, $body = null, $httpMethod = 'GET', array $headers = array(), array $options = array())
    {
        $request = $this->createRequest($httpMethod, $path, $body, $headers, $options);

        try {
            $response = $this->client->send($request);
        } catch (LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        } catch (TwoFactorAuthenticationRequiredException $e) {
            throw $e;
        } catch (RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        $this->lastRequest  = $request;
        $this->lastResponse = $response;

        return $response;
    }

    /**
     * Authenticate a user for the following requests.
     *
     * @param  string       $tokenOrLogin
     * @param  null|string  $password
     * @param  null|string  $authMethod
     * @throws InvalidArgumentException
     * @return null
     */
    public function authenticate($tokenOrLogin, $password = null, $method)
    {
        $this->addListener('before', array(
            new AuthListener($tokenOrLogin, $password, $method), 'onBefore'
        ));
    }

    /**
     * Get the last HTTP request.
     *
     * @return mixed
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * Get the last HTTP response.
     *
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Create a new HTTP request.
     *
     * @param  string  $httpMethod
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @param  array   $options
     * @return Request
     */
    protected function createRequest($httpMethod, $path, $body = null, array $headers = array(), array $options = array())
    {
        $request = $this->client->createRequest(
            $httpMethod,
            $path,
            ['headers' => array_merge($this->headers, $headers)],
            $options
        );

        $request->setBody($body);

        return $request;
    }
}

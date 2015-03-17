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
    protected $base_url = 'https://api.github.com/';

    protected $user_agent = 'caffeinated-github';

    protected $api_version = 'v3';

    protected $options = array(
        'timeout'     => 10,
    );

    protected $headers = array();

    private $lastResponse;

    private $lastRequest;

    public function __construct(array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);

        $client       = $client ?: new GuzzleClient(['base_url' => $this->base_url, 'defaults' => $this->options]);
        $this->client = $client;

        // $this->addListener('error', array(new ErrorListener($this->options), 'onError'));

        $this->clearHeaders();
    }

    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    public function clearHeaders()
    {
        $this->headers = array(
            'Accept'     => sprintf('application/vnd.github.%s+json', $this->api_version),
            'User-Agent' => sprintf('%s', $this->user_agent),
        );
    }

    public function addListener($eventName, $listener)
    {
        $this->client->getEmitter()->attach($listener[0]);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->client->getEmitter()->attach($subscriber);
    }

    public function get($path, array $parameters = array(), array $headers = array())
    {
        return $this->request($path, null, 'GET', $headers, array('query' => $parameters));
    }

    public function post($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'POST', $headers);
    }

    public function patch($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'PATCH', $headers);
    }

    public function delete($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'DELETE', $headers);
    }

    public function put($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'PUT', $headers);
    }

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

    public function authenticate($tokenOrLogin, $password = null, $method)
    {
        $this->addListener('before', array(
            new AuthListener($tokenOrLogin, $password, $method), 'onBefore'
        ));
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

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

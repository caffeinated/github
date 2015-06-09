<?php
namespace Caffeinated\Github\Api;

use Caffeinated\Github\Client;
use Caffeinated\Github\HttpClient\Message\ResponseMediator;

abstract class AbstractApi
{
    /**
     * @var Caffeinated\Github\Client
     */
    protected $client;

    /**
     * Create a new instance of AbstractApi.
     *
     * @param  Caffeinated\Github\Client  $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param  string  $path
     * @param  array   $parameters
     * @param  array   $requestHeaders
     * @return Guzzle\Http\EntityBodyInterface|mixed|string
     */
    protected function get($path, array $parameters = array(), $requestHeaders = array())
    {
        if (array_key_exists('ref', $parameters) and is_null($parameters['ref'])) {
            unset($parameters['ref']);
        }

        $response = $this->client->getHttpClient()->get($path, $parameters, $requestHeaders);
        $content  = ResponseMediator::getContent($response);

        if (is_array($content)) {
            return collect($content);
        }

        return $content;
    }
}

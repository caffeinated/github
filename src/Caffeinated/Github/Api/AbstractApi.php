<?php
namespace Caffeinated\Github\Api;

use Caffeinated\Github\Client;
use Caffeinated\Github\HttpClient\Message\ResponseMediator;

abstract class AbstractApi
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function get($path, array $parameters = array(), $requestHeaders = array())
    {
        if (array_key_exists('ref', $parameters) and is_null($parameters['ref'])) {
            unset($parameters['ref']);
        }

        $response = $this->client->getHttpClient()->get($path, $parameters, $requestHeaders);

        return ResponseMediator::getContent($response);
    }
}

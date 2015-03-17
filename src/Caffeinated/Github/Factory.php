<?php
namespace Caffeinated\Github;

use Caffeinated\Github\Authenticators\AuthenticatorFactory;
use Caffeinated\Github\Client;
use Caffeinated\Github\HttpClient\HttpClient;

class Factory
{
    protected $auth;

    protected $path;

    public function __construct(AuthenticatorFactory $auth, $path)
    {
        $this->auth = $auth;
        $this->path = $path;
    }

    public function make(array $config)
    {
        $http = $this->getHttpClient();

        return $this->getClient($http, $config);
    }

    protected function getHttpClient()
    {
        return new HttpClient();
    }

    protected function getClient(HttpClient $http, array $config)
    {
        $client = new Client($http);

        dd($config);

        dd(array_get($config, 'method'));

        return $this->auth->make(array_get($config, 'method'))->with($client)->authenticate($config);
    }
}

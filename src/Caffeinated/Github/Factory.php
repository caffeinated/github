<?php
namespace Caffeinated\Github;

use Caffeinated\Github\Authenticators\AuthenticatorFactory;
use Caffeinated\Github\Client;
use Caffeinated\Github\HttpClient\CachedHttpClient;

class Factory
{
    /**
     * @var Caffeinated\Github\Authenticators\AuthenticatorFactory
     */
    protected $auth;

    /**
     * @var string
     */
    protected $path;

    /**
     * Create a new instance of Factory.
     *
     * @param  Caffeinated\Github\Authenticators\AuthenticatorFactory  $auth
     * @param  string                                                  $path
     */
    public function __construct(AuthenticatorFactory $auth, $path)
    {
        $this->auth = $auth;
        $this->path = $path;
    }

    /**
     * Make a new GitHub client.
     *
     * @param  array  $config
     * @return Caffeinated\Github\Client
     */
    public function make(array $config)
    {
        $http = $this->getHttpClient();

        return $this->getClient($http, $config);
    }

    /**
     * Get the HTTP Client instance.
     *
     * @return Caffeinated\Github\HttpClient\HttpClient
     */
    protected function getHttpClient()
    {
        return new CachedHttpClient(['cache_dir' => storage_path('cache/caffeinated-github')]);
    }

    /**
     * Get the current client instance.
     *
     * @param  Caffeinated\Github\HttpClient\HttpClient  $http
     * @param  array                                     $config
     * @return Caffeinated\Github\Client
     */
    protected function getClient(CachedHttpClient $http, array $config)
    {
        $client = new Client($http);

        return $this->auth->make(array_get($config, 'method'))->with($client)->authenticate($config);
    }
}

<?php
namespace Caffeinated\Github\Authenticators;

use Caffeinated\Github\Client;

abstract class AbstractAuthenticator
{
    protected $client;

    public function with(Client $client)
    {
        $this->client = $client;

        return $this;
    }
}

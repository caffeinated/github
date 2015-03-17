<?php
namespace Caffeinated\Github\HttpClient\Interfaces;

use Guzzle\Http\Message\Response;

interface HttpClientInterface
{
    public function get($path, array $parameters = array(), array $headers = array());
    public function post($path, $body = null, array $headers = array());
    public function put($path, $body = null, array $headers = array());
    public function delete($path, $body = null, array $headers = array());
    public function request($path, $body, $httpMethod = 'GET', array $headers = array());
    public function setOption($key, $value);
    public function authenticate($tokenOrLogin, $password, $authMethod);
}

<?php
namespace Caffeinated\Github\HttpClient\Interfaces;

use Caffeinated\Github\Exceptions\InvalidArgumentException;
use Guzzle\Http\Message\Response;

interface HttpClientInterface
{
    /**
     * Send a GET request.
     *
     * @param  string  $path
     * @param  array   $parameters
     * @param  array   $headers
     * @return Response
     */
    public function get($path, array $parameters = array(), array $headers = array());

    /**
     * Send a POST request.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @return Response
     */
    public function post($path, $body = null, array $headers = array());

    /**
     * Send a PATCH request.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @return Response
     */
    public function patch($path, $body = null, array $headers = array());

    /**
     * Send a PUT request.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @return Response
     */
    public function put($path, $body = null, array $headers = array());

    /**
     * Send a DELETE request.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  array   $headers
     * @return Response
     */
    public function delete($path, $body = null, array $headers = array());

    /**
     * Send a request to the server, and receive a response.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  string  $httpMethod
     * @param  array   $headers
     * @return Response
     */
    public function request($path, $body, $httpMethod = 'GET', array $headers = array());

    /**
     * Set an option value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @throws InvalidArgumentException
     * @return null
     */
    public function setOption($key, $value);

    /**
     * Set HTTP headers.
     *
     * @param  array  $headers
     * @return null
     */
    public function setHeaders(array $headers);

    /**
     * Authenticate a user for the following requests.
     *
     * @param  string       $tokenOrLogin
     * @param  null|string  $password
     * @param  null|string  $authMethod
     * @throws InvalidArgumentException
     * @return null
     */
    public function authenticate($tokenOrLogin, $password, $authMethod);
}

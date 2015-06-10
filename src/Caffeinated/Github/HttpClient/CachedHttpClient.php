<?php
namespace Caffeinated\Github\HttpClient;

use DateTime;
use DateTimeZone;
use Caffeinated\Github\HttpClient\Cache\CacheInterface;
use Caffeinated\Github\HttpClient\Cache\FilesystemCache;

class CachedHttpClient extends HttpClient
{
	/**
	 * @var Caffeinated\Github\HttpClient\Cache\CacheInterface
	 */
	protected $cache;

	/**
	 * @var Guzzle\Http\Message\Response
	 */
	private $lastCachedResponse;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * Get the CacheInterface instance.
	 *
	 * @return Caffeinated\Github\HttpClient\Cache\CacheInterface
	 */
	public function getCache()
	{
		if (is_null($this->cache)) {
			$this->cache = new FilesystemCache($this->options['cache_dir'] ?: sys_get_temp_dir().DIRECTORY_SEPARATOR.'caffeinated-github-cache');
		}

		return $this->cache;
	}

	/**
	 * Set the CacheInterface instance.
	 *
	 * @return null
	 */
	public function setCache(CacheInterface $cache)
	{
		$this->cache = $cache;
	}

	/**
     * Send a request to the server and receive either a direct response or
	 * a cached one.
     *
     * @param  string  $path
     * @param  mixed   $body
     * @param  string  $httpMethod
     * @param  array   $headers
     * @return Response
     */
	public function request($path, $body = null, $httpMethod = 'GET', array $headers = array(), array $options = array())
	{
		$response = parent::request($path, $body, $httpMethod, $headers, $options);

		if (304 == $response->getStatusCode()) {
			$cacheResponse            = $this->getCache()->get($this->id);
			$this->lastCachedResponse = $cacheResponse;

			return $cacheResponse;
		}

		$this->getCache()->set($this->id, $response);

		return $response;
	}

	/**
     * Create a new HTTP request with If-Modified-Since headers.
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
		$request = parent::createRequest($httpMethod, $path, $body, $headers, $options);

		$this->id = $path;

		if (array_key_exists('query', $options) and ! empty($options['query'])) {
			$this->id .= '?'.$request->getQuery();
		}

		if ($modifiedAt = $this->getCache()->getModifiedSince($this->id)) {
			$modifiedAt = new DateTime('@'.$modifiedAt);
			$modifiedAt->setTimezone(new DateTimeZone('GMT'));

			$request->addHeader('If-Modified-Since', sprintf('%s GMT', $modifiedAt->format('l, d-M-y H:i:s')));
		}

		if ($etag = $this->getCache()->getETag($this->id)) {
			$request->addHeader('If-None-Match', $etag);
		}

		return $request;
	}

	/**
	* Get the last HTTP response.
	*
	* @return mixed
	*/
	public function getLastResponse($force = false)
	{
		$lastResponse = parent::getLastResponse();

		if (304 != $lastResponse->getStatusCode()) {
			$force = true;
		}

		return ($force) ? $lastResponse : $this->lastCachedResponse;
	}
}

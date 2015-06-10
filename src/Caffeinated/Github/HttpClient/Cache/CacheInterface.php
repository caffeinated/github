<?php
namespace Caffeinated\Github\HttpClient\Cache;

use Guzzle\Http\Message\Response;

interface CacheInterface
{
	/**
	 * Checks if the response has been cached already.
	 *
	 * @param  string  $id
	 * @return bool
	 */
	public function has($id);

	/**
	 * Returns the "modified since" timestamp from the cached resource.
	 *
	 * @param  string  $id
	 * @return null|int
	 */
	public function getModifiedSince($id);

	/**
	 * Get's the ETag from the cached resource.
	 *
	 * @param  string  $id
	 * @return null|string
	 */
	public function getETag($id);

	/**
	 * Gets the cached resource.
	 *
	 * @param  string  $id
	 * @return Response
	 */
	public function get($id);

	/**
	 * Creates a new cached response on the server.
	 *
	 * @param  string    $id
	 * @param  Response  $response
	 * @throws InvalidArgumentException
	 * @return null
	 */
	public function set($id, Response $response);
}

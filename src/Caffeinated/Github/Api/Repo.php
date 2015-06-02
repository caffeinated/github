<?php
namespace Caffeinated\Github\Api;

use Caffeinated\Github\Api\Repository\Contents;
use Caffeinated\Github\Api\Repository\Releases;

class Repo extends AbstractApi
{
	/**
	 * Get Repository Information
	 *
	 * Returns an array of information about the specified repository.
	 *
	 * @param  string  $owner
	 * @param  string  $repository
	 * @return array
	 */
	public function show($owner, $repository)
	{
		return $this->get('repos/'.rawurlencode($owner).'/'.rawurlencode($repository));
	}

	/**
	 * Returns a new Contents instance.
	 *
	 * @return Contents
	 */
	public function contents()
	{
		return new Contents($this->client);
	}

	/**
	 * Returns a new Releases instance.
	 *
	 * @return Releases
	 */
	public function releases()
	{
		return new Releases($this->client);
	}
}

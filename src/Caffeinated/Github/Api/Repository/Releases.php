<?php
namespace Caffeinated\Github\Api\Repository;

use Caffeinated\Github\Api\AbstractApi;

class Releases extends AbstractApi
{
	/**
	 * Returns an array of all repository releases.
	 *
	 * @param  string  $owner
	 * @param  string  $repository
	 * @return array
	 */
	public function all($owner, $repository)
	{
		return $this->get('repos/'.rawurlencode($owner).'/'.rawurlencode($repository).'/releases');
	}
}

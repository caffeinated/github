<?php
namespace Caffeinated\Github\Api\Repository;

use Caffeinated\Github\Api\AbstractApi;

class Releases extends AbstractApi
{
	public function all($owner, $repo)
	{
		return $this->get('repos/'.rawurlencode($owner).'/'.rawurlencode($repo).'/releases');
	}
}

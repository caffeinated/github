<?php
namespace Caffeinated\Github\Api;

use Caffeinated\Github\Api\Repository\Contents;
use Caffeinated\Github\Api\Repository\Releases;

class Repo extends AbstractApi
{
	public function show($username, $repository)
	{
		return $this->get('repos/'.rawurlencode($username).'/'.rawurlencode($repository));
	}

	public function contents()
	{
		return new Contents($this->client);
	}

	public function releases()
	{
		return new Releases($this->client);
	}
}

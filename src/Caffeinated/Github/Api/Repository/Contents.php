<?php
namespace Caffeinated\Github\Api\Repository;

use Caffeinated\Github\Api\AbstractApi;

class Contents extends AbstractApi
{
	public function archive($username, $repository, $format = 'tarball', $reference = null)
	{
		if (! in_array($format, ['tarball', 'zipball'])) {
			$format = 'tarball';
		}

		return $this->get('repos/'.rawurlencode($username).'/'.rawurlencode($repository).'/'.rawurlencode($format).
			(! is_null($reference) ? ('/'.rawurlencode($reference)) : ''));
	}
}

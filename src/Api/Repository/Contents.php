<?php
namespace Caffeinated\Github\Api\Repository;

use Caffeinated\Github\Api\AbstractApi;

class Contents extends AbstractApi
{
	/**
	 * Get archive for the given repository.
	 *
	 * @return mixed
	 */
	public function archive($owner, $repository, $format = 'tarball', $reference = null)
	{
		if (! in_array($format, ['tarball', 'zipball'])) {
			$format = 'tarball';
		}

		return $this->get('repos/'.rawurlencode($owner).'/'.rawurlencode($repository).'/'.rawurlencode($format).
			(! is_null($reference) ? ('/'.rawurlencode($reference)) : ''));
	}

	/**
	 * Download a file from the given repository.
	 *
	 * @return null|string
	 */
	public function download($owner, $repository, $path, $reference = null)
	{
		$file = $this->show($owner, $repository, $path, $reference);

		return base64_decode($file['content']) ?: null;
	}

	/**
	 * Get contents of any file or directory in the given repository.
	 *
	 * @return array
	 */
	public function show($owner, $repository, $path = null, $reference = null)
	{
		$url = 'repos/'.rawurlencode($owner).'/'.rawurlencode($repository).'/contents';

		if (! is_null($path)) {
			$url .= '/'.rawurlencode($path);
		}

		return $this->get($url, ['ref' => $reference]);
	}
}

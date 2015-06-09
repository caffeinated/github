<?php
namespace Caffeinated\Github\Api\Repository;

use Caffeinated\Github\Api\AbstractApi;

class Releases extends AbstractApi
{
	/**
	 * List releases for a repository.
	 *
	 * Information about published releases are available to everyone. Only
	 * users with push access will receive listings for draft releases.
	 *
	 * @param  string  $owner
	 * @param  string  $repository
	 * @return array
	 */
	public function all($owner, $repository)
	{
		return $this->get('repos/'.rawurlencode($owner).'/'.rawurlencode($repository).'/releases');
	}

	/**
	 * Get a single release.
	 *
	 * @param  string  $owner
	 * @param  string  $repository
	 * @param  int     $id
	 * @return array
	 */
	public function show($owner, $repository, $id)
	{
		return $this->get('repos/'.rawurlencode($owner).'/'.rawurlencode($repository).'/releases/'.rawurlencode($id));
	}

	/**
	 * Get the latest release.
	 *
	 * View the latest published release for the repository.
	 *
	 * @param  string  $owner
	 * @param  string  $repository
	 * @return array
	 */
	public function latest($owner, $repository)
	{
		return $this->get('repos/'.rawurlencode($owner).'/'.rawurlencode($repository).'/releases/latest');
	}

	/**
	 * Get a release by tag name.
	 *
	 * Get a release with the specified tag. Users must have push access to the
	 * repository to view draft releases.
	 *
	 * @param  string  $owner
	 * @param  string  $repository
	 * @param  string  $tag
	 * @return array
	 */
	public function tag($owner, $repository, $tag)
	{
		return $this->get('repos/'.rawurlencode($owner).'/'.rawurlencode($repository).'/releases/tags/'.rawurlencode($tag));
	}
}

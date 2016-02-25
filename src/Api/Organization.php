<?php
namespace Caffeinated\Github\Api;

class Organization extends AbstractApi
{
	/**
	 * List organization repositories.
	 *
	 * List repositories for the specified organization.
	 *
	 * @param  string  $organization
	 * @param  string  $type
	 * @return array
	 */
	public function repositories($organization, $type = 'all')
	{
		return $this->get('orgs/'.rawurlencode($organization).'/repos', ['type' => $type]);
	}
}

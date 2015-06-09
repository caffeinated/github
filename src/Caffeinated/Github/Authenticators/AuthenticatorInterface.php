<?php
namespace Caffeinated\Github\Authenticators;

use Caffeinated\Github\Client;

interface AuthenticatorInterface
{
	/**
	 * Set the client to perform the authentication on.
	 *
	 * @param  Caffeinated\Github\Client;
	 * @return Caffeinated\Github\Authenticators\AuthenticatorInterface
	 */
	public function with(Client $client);

	/**
	 * Authenticate the client and return them.
	 *
	 * @param  array  $config
	 * @throws InvalidArgumentException
	 * @return Caffeinated\Github\Client
	 */
	public function authenticate(array $config);
}

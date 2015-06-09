<?php
namespace Caffeinated\Github\Api;

class User extends AbstractApi
{
    /**
     * Get a single user.
     *
     * @param  string  $username
     * @return array
     */
    public function show($username)
    {
        return $this->get('users/'.rawurlencode($username));
    }

    /**
     * List organizations for the specified user.
     *
     * OAuth scope requirements
     * Currently, OAuth requests always receive the user’s public organization
     * memberships, regardless of the OAuth scopes associated with the request.
     * If the OAuth authorization has user or read:org scope, the response also
     * includes private organization memberships.
     *
     * With the new Organization Permissions API, this method will only return
     * organizations that your authorization allows you to operate on in some
     * way (e.g., you can list teams with read:org scope, you can publicize your
     * organization membership with user scope, etc.). Therefore, this API will
     * require at least user or read:org scope. OAuth requests with insufficient
     * scope will receive a 403 Forbidden response.
     *
     * @param  string  $username
     * @return array
     */
    public function organizations($username)
    {
        return $this->get('users/'.rawurlencode($username).'/orgs');
    }
}

<?php
namespace Caffeinated\Github\Api;

class User extends AbstractApi
{
    public function show($username)
    {
        return $this->get('users/'.rawurlencode($username));
    }
}

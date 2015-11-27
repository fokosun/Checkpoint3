<?php
/**
 * Created by Florence Okosun.
 * Project: Checkpoint Two
 * Date: 11/4/2015
 * Time: 4:07 PM
 */

namespace Florence;

class User extends AuthController
{
    private $username;
    private $password;
    private $token;
    private $token_expire;

    /**
    * Create an User instance
    */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setTokenExpire($token_expire)
    {
        $this->token_expire = $token_expire;
    }

    public function getTokenExpire()
    {
        return $this->token_expire;
    }
}

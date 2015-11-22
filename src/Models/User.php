<?php

namespace Florence;

class User {

    /**
    * @var string $username
    */
    private $username;

    /**
    * @var string $password
    */
    private $password;

    /**
    * @var $token
    */
    private $token;

    /**
    * @var $token_expire
    */
    private $token_expire;

    /**
    * user instance constructor
    */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
    * @return string
    * get the username for a user instance
    */
    public function getUserName()
    {
        return $this->username;
    }

    /**
    * @return string
    * get the password for a user instance
    */
    public function getPassword()
    {
        return $this->password;
    }

    /**
    * @param $token
    * set the verification token for the user instance
    */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
    * @param $token
    * set expiry time for the token
    */
    public function setTokenExpire($tokenExpire)
    {
        $this->token_expire = $tokenExpire;
    }

    /**
    * @param $token
    * get the verification token for the user instance
    */
    public function getToken()
    {
        return $this->token;
    }

    /**
    * @param $token
    * get the expiry time for the token
    */
    public function getTokenExpire()
    {
        return $this->token_expire;
    }
}

<?php

namespace Florence;

class User {
    protected $username;
    protected $password;
    protected $token;
    protected $token_expire;
    protected $created_at;
    protected $updated_at;

    public function __construct($username, $password, $token, $created_at, $updated_at)
    {
        $this->username = $username;
        $this->password = $password;
        $this->token = $token;
        $this->token_expire = $token_expire;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getToken()
    {
        $this->token = base64_encode("hello, world");
        var_dump($this->token);
    }

    public function getTokenExpire()
    {
        return $this->token_expire;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}
<?php

namespace Florence;

use Florence\User;

class UserController
{
    public function __construct()
    {
        $this->user = new User();
    }

    /**
    * get db connection
    */
    public function getConnection($connection = null)
    {
        if(is_null($connection))
        {
            return new DBConnection();
        }
    }

    /**
    * creates a new user in the user table
    */
    public function register()
    {
        $connection = $this->getConnection();

        $username = $this->user->getUserName();
        $password = $this->user->getPassword();
        $token = $this->user->getToken();
        $token_expire = $this->user->getTokenExpire();
        $created_at = $this->user->getCreatedAt();
        $updated_at = $this->user->getUpdatedAt();

        $create = "INSERT INTO users(username, password, token, token_expire, created_at,updated_at)
        VALUES (. " . $username . "," . $password . "," . $token . "," . $token_expire . ","
            . $created_at . "," . $updated_at . ")";

        $stmt = $connection->prepare($create);
        try {
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count < 1) {
                throw new RecordExistAlreadyException('Record exist already.');
            }
        } catch (RecordExistAlreadyException $e) {

        return $e->getExceptionMessage();

        } catch(PDOException $e){

            return $e->getExceptionMessage();

        }
    }

    public function login()
    {
        //generate token and log user in
    }

    public function logout()
    {
        // destroy session
    }

}

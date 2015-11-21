<?php

namespace Florence;

use PDOException;
use Florence\User;

class AuthController
{
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
    public function create()
    {
        $connection = $this->getConnection();

        $user = new User;
        $username = $user->getUserName();
        $password = $user->getPassword();
        $token = $user->getToken();
        $token_expire = $user->getTokenExpire();
        $created_at = $user->getCreatedAt();
        $updated_at = $user->getUpdatedAt();

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

    public function update(User $user, $id)
    {
        $connection = $this->getConnection();

        $update = "UPDATE users SET" .
                                "username=" . $username . "," .
                                "password=" . $password . "," .
                                "token=" . $token . "," .
                                "token_expire=" . $token_expire . "," .
                                "created_at=" . $created_at. "," .
                                "updated_at=" . $updated_at .
                    "WHERE id =" . $id;
    }

    public function delete($id)
    {
        //deletes everything from users table where id = $id
    }

    public function invalidateSeesion()
    {

    }
}

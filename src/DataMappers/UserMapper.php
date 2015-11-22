<?php

namespace Florence;

use Florence\User;

class UserMapper {
    // this guy does all the CRUD tingy

    /**
    * get db connection
    */
    public function getConnection()
    {
        return new Connection();
    }

    public function find($id)
    {
        try {
            $find = "SELECT * FROM users WHERE id=". $id;
            $stmt = $this->getConnection()->prepare($find);
            $stmt->execute();
            $result = $stmt->fetchAll();
                if (empty($result)) {
                    throw new RecordDoesNotExistException("User does not exist!");
                } else {
                    return $result[0];
                }
            } catch (RecordDoesNotExistException $e) {
                return $e->getExceptionMessage();
            } catch(PDOException $e) {
                return $e->getExceptionMessage();
        }
    }

    public function all()
    {
        try {
            $all = "SELECT * FROM users";
            $stmt = $this->getConnection()->query($all);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($result)) {
                    throw new RecordDoesNotExistException("There are no records at this time");
                }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    return $result[0];
    }

    public function delete($id)
    {
        try {
            $delete = "DELETE * FROM users WHERE id=". $id;
            $stmt = $this->getConnection()->prepare($delete);
            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count > 0) {
                    return true;
                } else {
                    throw new RecordDoesNotExistException('Record does not exist!');
                }

        } catch (RecordDoesNotExistException $e) {
            return $e->getExceptionMessage();
        } catch(PDOException $e) {
            return $e->getExceptionMessage();
        }
    }

    public function save(User $user)
    {
        $sql = "INSERT INTO users(username, password, token, token_expire) VALUES(?,?,?,?)";
        $stmt = $this->getConnection()->prepare($sql);

        $username = $user->getUserName();
        $password = $user->getPassword();
        $token = $user->getToken();
        $token_expire = $user->getTokenExpire();

        //bind params to statement
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $stmt->bindParam(3, $names);
        $stmt->bindParam(4, $token);
        $stmt->bindParam(5, $tokenExpire);

        try {
            $stmt->execute();
                if ($count > 0) {
                    return true;
                } else {
                    throw new RecordDoesNotExistException('Record does not exist!');
                }
        } catch(PDOException $e) {
            return $e->getExceptionMessage();
        }
    }
}

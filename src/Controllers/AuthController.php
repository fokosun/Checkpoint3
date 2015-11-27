<?php
/**
* Emoji API
* This script provides a RESTful API interface for Emojis
* Author: Florence Okosun
*/

namespace Florence;

use Slim\Slim;
use Exception;
use PDOException;
use Florence\User;
use Florence\Connection;

abstract class AuthController
{
    /**
    * @var $className
    * @var $table
    * @return $table
    */
    public static function getTableName()
    {
        $className = explode('\\', get_called_class());
        $table = strtolower(end($className) .'s');

        return $table;
    }

    public static function register(Slim $app)
    {
        echo self::getTableName();

        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();

        $username = $app->request->params('username');
        $password = $app->request->params('password');
        $token = "";
        $token_expire = date('Y-m-d H:i:s');

        try
        {
            $sql = "INSERT INTO " . self::getTableName() . "(username, password, token, token_expire)
            VALUES (?, ?, ?, ?)";

            $stmt = $connection->prepare($sql);

            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $password);
            $stmt->bindParam(3, $token);
            $stmt->bindParam(4, $token_expire);

            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $response->body(json_encode([
                  'status'  => 200,
                  'message' => 'Record created'
                ]));
            } else {
                $response->body(json_encode([
                  'status'  => 400,
                  'message' => 'Error processing request'
                ]));
            }

        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return $response;

    }

    public static function logout(Slim $app)
    {
        echo "loggedout";
    }

}

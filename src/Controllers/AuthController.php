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

    public static function register(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();

        try
        {
            $sql = "INSERT INTO users (username, password, token, token_expire)VALUES (?, ?, ?, ?)";

            $username = $app->request->params('username');
            $password = $app->request->params('password');
            $token = "";
            $token_expire = date('Y-m-d H:i:s');

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

    public static function login(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();
    }

    /**
     * Create and return a token
     *
     * @return string
     */
     public function getToken()
     {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $tokenExpire = date('Y-m-d H:i:s', strtotime('+ 1 hour'));
        return json_encode([
          'expiry'=>$tokenExpire,
          'token' => $token,
          'username' => $this->username,
          'password' => $this->password
        ]);
     }

}

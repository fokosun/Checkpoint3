<?php

namespace Florence;

use Slim\Slim;
use Florence\User;
use Illuminate\Database\QueryException;

class Authorization
{
    public static function isAuthorised($app, $token)
    {
        if($token == "" || $token == NULL) {
            $app->halt(401, json_encode(['status' => 402, 'message' => 'Token required']));
        }
        return self::isValid($app, $token);
    }

    /**
    * @param $username
    * @param $password
    */
    public static function isValid($app, $token)
    {
        try {
            $user = User::where('token', $token)->first();

            if($user !== NULL) {
               $expiry = self::isTokenExpired($token);

            if($expiry == true) {
                $app->halt(401, json_encode(['status'=> 401, 'message' => 'Session expired']));
            } else {
                $status = json_encode(['status'=>200,
                'username'      => $user['username'],
                'password'      => $user['password'],
                'token'         => $user['token'],
                'token_expire'  => $user['token_expire']
                ]);
            }
            return $status;
            } else {
                $app->halt(401, json_encode(['status'=> 401, 'message' => 'Check your token!']));
            }

        } catch(QueryException $e) {
            $app->halt(401, json_encode(['status'=> 401, 'message' => 'Wrong token']));
        }
    }

    public static function isTokenExpired($token)
    {
        $user = User::where('token', $token)->first();

        $token_expire = $user['token_expire'];
        $currTime = date('Y-m-d H:i:s');

        if($token_expire < $currTime) {
            return true;
        } else {
            return false;
        }
    }
}

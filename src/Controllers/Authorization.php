<?php

namespace Florence;

use Slim\Slim;
use Florence\User;
use Illuminate\Database\QueryException;

class Authorization
{
    public static function isAuthorised($token)
    {
        if(empty($token)) {

            return false;
        }
        return self::isValid($token);
    }

    /**
    * @param $username
    * @param $password
    */
    public function isValid($token)
    {
        try {
            $user = User::where('token', $token)->first();
            if (! empty($user)) {
                $expiry = self::isTokenExpired($token);
                    if($expiry == true) {
                        $status = json_encode(['status'=>301,'message'=>'session expired!']);
                    } else {
                        $status = json_encode(['status'=>200,
                        'username'  =>  $user['username'],
                        'password'  =>  $user['password'],
                        'token' => $user['token'],
                        'token_expire' => $user['token_expire']
                        ]);
                    }
            } else {
                $status = json_encode(['status'=> 500,'message' => 'Authorization required']);
            }
        } catch(QueryException $e) {
            $response->body(json_encode(['status' => 404, 'message' => 'Invalid credentials!']));
        }
        return $status;
    }

    public function isTokenExpired($token)
    {
        $user = User::where('token', $token)->first();

        $token_expire = $user['token_expire'];
        $currTime = date('Y-m-d H:i:s');

        if($token_expire < $currTime) {
            return true;
        }
        return false;
    }
}

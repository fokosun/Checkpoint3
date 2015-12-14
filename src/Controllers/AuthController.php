<?php

namespace Florence;

use Slim\Slim;
use Florence\User;
use Illuminate\Database\QueryException;

class AuthController {

    /**
    * @param Slim $app
    * @return $response
    */
    public static function register(Slim $app)
    {
        $status = [];
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');
        $username = $app->request->params('username');
        $password = password_hash($app->request->params('password'), PASSWORD_BCRYPT);

        $data = json_decode(self::validate($app, $username, $password));
        foreach ($data as $key=>$value) {
            array_push($status, $value);
        }
        if ($status[0] == 200) {
            $username = $status[1];
            $password = $status[2];
        }

        try {
            $user = new User;
            $user->username = $username;
            $user->password = $password;
            $user->save();
            $response->body(json_encode([
                'status'       => 200,
                'message'      => 'Way to go ' . $username . '!'
                ]));
            return $response;
        } catch(QueryException $e) {
            $app->halt(403, json_encode(['status' => 403, 'message' => 'User exists already!']));
        }
    }

    /**
    * @param $username, $password
    * @return $status
    * validates fields and entries
    */
    public static function validate($app, $username, $password)
    {
        if(! isset($username, $password)) {
            $app->halt(401, json_encode(['status' => 401, 'message' => 'Registration params required']));
        } else {
            $token = self::tokenize($username, $password);
            $status = $token;
            return $status;
        }
    }

    /**
    * @param Slim $app
    * @return $response
    */
    public static function login(Slim $app)
    {
        $status = [];
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');
        $token    = $app->request->headers('Authorization');
        $username = $app->request->params('username');
        $password = $app->request->params('password');

        $jsonData = self::validate($app, $username, $password);
        $data = json_decode($jsonData);
        foreach ($data as $key=>$value) {
            array_push($status, $value);
        }
        if (! $status[0] == 200) {
            $code = $status[0];
            $message = $status[1];
            $response->body(json_encode(['status' => $code, 'message' => $message]));
        } else {
            $username = $status[1];
            $password = $status[2];
            $token = $status[3];
            $token_expire = $status[4];

            $credentials = self::validateCredentials($app, $username, $password);
            if($credentials) {
                User::where('username', $username)
                ->update(['token' => $token, 'token_expire' => $token_expire]);
                $response->body(json_encode(['status' => 200,
                        'message' => 'you are logged in ' . $username,
                        'token' => $token,
                        'token expires' => $token_expire
                ]));
                $response->header('Authorization', $token);
            }
        }
        return $response;
    }

    public static function validateCredentials($app, $username, $password)
    {
        try {
            $user = User::where('username', $username)->first();
            $pass = User::where('password', $password)->first();
            if($user == null || $pass == null) {
                $app->halt(401, json_encode(['status' => 401, 'message' => 'Wrong credentials']));
            } else {
                return true;
            }
        } catch(QueryException $e) {
            $app->halt(401, json_encode(['status' => 401, 'message' => 'Wrong credentials']));
        }
    }

    /**
    * @return json
    * generate token
    */
    private static function tokenize($username, $password)
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $tokenExpire = date('Y-m-d H:i:s', strtotime('+ 1 hour'));

        return json_encode([
            'status' => 200,
            'username' => $username,
            'password' => $password,
            'token' => $token,
            'token_expire'=>$tokenExpire
        ]);
    }

    /**
    * @param Slim $app
    * @return $response
    */
    public static function logout(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $username = $app->request->params('username');
        $password = $app->request->params('password');

        $credentials = self::validateCredentials($app, $username, $password);
        if($credentials) {
            User::where('username', $username)->update(['token' => null, 'token_expire' => null]);
            $response->body(json_encode(['status' => 200, 'message' => 'session destroyed success!']));
        return $response;
        }
    }
}

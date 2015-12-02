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
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $username = $app->request->params('username');
        $password = $app->request->params('password');

        $user = new User;

        $user->username = $username;
        $user->password = $password;

        try {
            $user->save();
            $response->body(json_encode(['status' => 200,
                'message' => 'Way to go ' . $username . '!',
                'username' => $username,
                'password' => $password]));
        } catch(QueryException $e) {
            $response->body(json_encode(['status' => 401, 'message' => 'Sorry, this user exists already!']));
        }

        return $response;
    }

    /**
    * @param Slim $app
    * @return $response
    */
    public static function login(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $username = $app->request->params('username');
        $password = $app->request->params('password');

        $data = self::tokenize($username, $password);
        $data  = json_decode($data, true);

        $token = $data['token'];
        $token_expire = $data['expiry'];

        try {
            if (array_key_exists('token', $data)) {
                $curr_surfer = User::where('username', $username)->get();
                $check = count($curr_surfer);

                if($check > 0) {
                    User::where('username', $username)
                    ->update(['token' => $token, 'token_expire' => $token_expire]);
                    $response->body(json_encode($data));
                } else {
                    $response->body(json_encode(['status' => 401, 'message' => 'You need to register!']));
                }
                return $response;
            }

        } catch(QueryException $e) {
            $response->body(json_encode(['status' => 401, 'message' => 'You need to register!']));
        }
        $response->header('Authorization', $data['token']);
        return $response;
    }

    /**
    * @param Slim $app
    * @return $response
    */
    public static function logout(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $token   = $app->request->headers->get('Authorization');

        try {
            $u = User::where('token', $token)
                    ->update(['token' => null, 'token_expire' => null]);
            if($u > 0) {
                $response->body(json_encode(['status' => 'success']));
            } else {
                $response->body(json_encode(['status' => 'Token mismatch']));
            }
        } catch(QueryException $e) {
            $response->body(json_encode(['status' => 404, 'message' => 'Error processing request']));
        }

        // var_dump($u);
        return $response;
    }

    private static function tokenize($username, $password)
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $tokenExpire = date('Y-m-d H:i:s', strtotime('+ 1 hour'));

        return json_encode([
          'expiry'=>$tokenExpire,
          'token' => $token,
          'username' => $username,
          'password' => $password
        ]);
    }
}

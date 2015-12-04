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

        $data = self::validate($username, $password);
        $data = json_decode($data);

        $status = [];
            foreach ($data as $key=>$value) {
                array_push($status, $value);
            }

        if ($status[0] == 200) {
            $username = $status[1];
            $password = $status[2];
            $token = $status[3];
            $token_expire = $status[4];
        } else {
            $response->body(json_encode(['status' => 500, 'message' => 'Unknownn error']));
        }

        $user = new User;

        $user->username     = $username;
        $user->password     = $password;
        $user->token        = $token;
        $user->token_expire = $token_expire;

        try {
            $user->save();
            $response->body(json_encode(['status' => 200,
                'message'      => 'Way to go ' . $username . '!',
                'username'     => $username,
                'password'     => $password,
                'token'        => $token,
                'token_expire' => $token_expire
                ]));

        } catch(QueryException $e) {
            $response->body(json_encode(['status' => 401, 'message' => 'User exists already!']));
        }

        return $response;
    }

    /**
    * @param $username, $password
    * @return $status
    * validates fields and entries
    */
    public static function validate($username, $password) {

        if(empty($username) || empty($password)) {
            $status = json_encode(['status'=>'204',
                'message'=>'No content!'
                ]);
        } else {
            $token = self::tokenize($username, $password);
            $status = $token;
        }

        return $status;
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


        if($token == NULL) {
            $response->body(json_encode(['status' => 401, 'message' => 'You have no authorization!']));
        } else {
            $data = self::validate($username, $password);
        $data = json_decode($data);

        $status = [];
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
                $response->body(json_encode(['status' => $username, 'username' => $password]));
            }
        }
        return $response;
    }

    /**
    * @param $username
    * @param $password
    */
    public function isValid($username, $password, $token)
    {
        try {
            $user = User::where('token', $token)->first();
            if (! empty($user)) {
                if ($user['password'] === $password && $user['username'] === $username) {
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
                    $status = json_encode(['status'=>401,'message'=>'Authorization required']);
                }
            } else {
                $status = json_encode(['status'=> 500,'message' => 'Authorization required']);
            }
        } catch(QueryException $e) {
            $response->body(json_encode(['status' => 404, 'message' => 'Invalid credentials!']));
        }
        return $status;
    }

    /**
    * @return boolead
    * checks if token is expired
    */
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

    /**
    * @return string
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

        $token = $app->request->headers('Authorization');

        try {
            $destroy = User::where('token', $token)
                    ->update(['token' => null, 'token_expire' => null]);
            if($destroy > 0) {
                $response->body(json_encode(['status' => 200, 'message' => 'session destroyed success!']));
            } else {
                $response->body(json_encode(['status' => 'Token mismatch']));
            }
        } catch(QueryException $e) {
            $response->body(json_encode(['status' => 404, 'message' => 'Error processing request']));
        }

        return $response;
    }

}

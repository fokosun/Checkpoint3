<?php

namespace Florence;

use Slim\Slim;
use Florence\Emoji;
use Florence\Authorization;

class EmojiController {

    /**
    * @param Slim $app
    * @return $response
    */
    public static function create(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $name       = $app->request->params('name');
        $emojichar  = $app->request->params('emojichar');
        $keywords   = $app->request->params('keywords');
        $category   = $app->request->params('category');

        $token = $app->request->headers('Authorization');

        $auth = Authorization::isAuthorised($app, $token);
        if($auth) {
            $data = json_decode($auth);
            $status = [];

                foreach ($data as $key=>$value) {
                    array_push($status, $value);
                }

                if ($status[0] == 200) {
                     $username = $status[1];
                }

            $emoji = new Emoji;

            $emoji->name        = $name;
            $emoji->emojichar   = $emojichar;
            $emoji->keywords    = $keywords;
            $emoji->category    = $category;
            $emoji->created_by  = $username;

                try {
                    $emoji->save();

                    $response->body(json_encode(['status' => 200, 'message' => 'emoji created']));

                } catch(QueryException $e) {
                    $app->halt(500, json_encode(['status'=> 500, 'message' => 'Error processing request']));
                }

            return $response;

        } else {
            return $auth;
        }
    }

    /**
    * @param Slim $app
    * @return $response
    */
    public static function getAll(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        try {
            $emojis = Emoji::all();
            $count  = count($emojis);

            if($count < 1) {
                $response->body(json_encode(['status' => 404, 'message' => 'No Emojis at this time!']));

            } else {
                $result = json_encode($emojis);
                $response->body($result);
            }
        } catch(Exception $e) {
            $response->body(json_encode(['message' => $e->getExceptionMessage()]));
        }

        return $response;

    }

    /**
    * @param $id
    * @param Slim $app
    * @return $response
    */
    public static function find(Slim $app, $id)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $token = $app->request->headers('Authorization');

        $auth = Authorization::isAuthorised($token);
        if($auth) {

            try {
                $emoji = Emoji::find($id);
                $count = count($emoji);
                if($count < 1) {
                    $app->halt(404, json_encode(['status'=> 404, 'message' => 'Emoji not found']));
                } else {
                    $result = json_encode($emoji);
                    $response->body($result);
                }
            } catch(Exception $e) {
            $app->halt(404, json_encode(['status'=> 404, 'message' => 'Emoji not found']));
        }
        return $response;
        } else {
            return $auth;
        }
    }

    /**
    * @param $field, $criteria
    * @param Slim $app
    * @return $response
    */
    public static function findBy(Slim $app, $field, $criteria)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        try {
            $emojis = Emoji::where($field, '=', $criteria)->get();
            $count = count($emojis);

            if($count < 1) {
                $response->body(json_encode(
                    ['status' => 404, 'message' => $criteria . ' not found. try something else']));

            } else {
                $result = json_encode($emojis);
                $response->body($result);
            }
        } catch(Exception $e) {
            $response->body(json_encode(['message' => $e->getExceptionMessage()]));
        }

        return $response;
    }

    /**
    * @param $id
    * @param Slim $app
    * @return $response
    */
    public static function update(Slim $app, $id)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $token = $app->request->headers('Authorization');

        $auth = Authorization::isAuthorised($app, $token);

        if($auth) {
            $update = Emoji::find($id);
            if ($update) {
                $columns = $app->request->isPut() ? $app->request->put() : $app->request->patch();
                foreach ($columns as $key => $value) {
                    $update->$key = $value;
                }
                $update->updated_at = date('Y-m-d H:i:s');
                $update->save();
                $response->body(json_encode(['status' => 200, 'message' => 'successfully updated!']));
            } else {
                $response->body(json_encode(['status' => 401, 'message' => 'Emoji not found']));
            }
        }
        return $response;
    }

    /**
    * @param $id
    * @param Slim $app
    * @return string
    */
    public static function delete(Slim $app, $id)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $token = $app->request->headers('Authorization');

        $auth = Authorization::isAuthorised($app, $token);

        if($auth) {
            $delete = Emoji::destroy($id);

            if ($delete == 1) {
                $response->body(json_encode(['status' => 200, 'message' => 'successfully deleted!']));
            } else {
                $response->body(json_encode(['status' => 401, 'message' => 'Emoji not found']));
            }
        }
        return $response;
    }
}

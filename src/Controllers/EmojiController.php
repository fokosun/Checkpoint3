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

        $name = $app->request->params('name');
        $emojichar = $app->request->params('emojichar');
        $keywords = $app->request->params('keywords');
        $category = $app->request->params('category');

        $token = $app->request->headers('Authorization');
        $auth = Authorization::isAuthorised($app, $token);
        $data = json_decode($auth);
        $status = [];
        foreach ($data as $key=>$value) {
            array_push($status, $value);
        }

        try {
            $emoji = new Emoji;
            if($status[0] == 200) {
                if (self::validateParams($app, $name, $emojichar, $keywords, $category)) {
                    $emoji->name        = $name;
                    $emoji->emojichar   = $emojichar;
                    $emoji->keywords    = $keywords;
                    $emoji->category    = $category;
                    $emoji->created_by  = $status[1];
                    $emoji->save();
                    $response->body(json_encode(['status' => 200, 'message' => 'emoji created']));
                }
            } else {
                $app->halt(401, json_encode(['status'=> $status[0], 'message' => $status[1]]));
            }
        } catch(QueryException $e) {
            $app->halt(401, json_encode(['status'=> 401, 'message' => 'Error processing request']));
        }
        return $response;
    }

    /**
    * emoji params validator
    */
    public static function validateParams($app, $name, $emojichar, $keywords, $category) {
        if($name == "" || $name == NULL || $emojichar == "" || $emojichar == NULL
            || $category == "" || $category == NULL || $keywords == "" || $keywords == NULL) {
            $app->halt(401, json_encode(['status' => 401, 'message' => 'Emoji Params required']));
        } else {
            return true;
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
                $response->body(json_encode(['status' => 204, 'message' => 'No Emojis at this time!']));
                return $response;
            }
            foreach ($emojis as $key) {
                $key->keywords = explode(",", $key->keywords);
            }
            $response->body($emojis);
            return $response;

        } catch(Exception $e) {
            $response->body(json_encode(['message' => $e->getExceptionMessage()]));
            return $response;
        }
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

        $name = $app->request->params('name');
        $emojichar = $app->request->params('emojichar');
        $keywords = $app->request->params('keywords');
        $category = $app->request->params('category');

        try {
            $emoji = Emoji::find($id);
            if(count($emoji) < 1) {
                $app->halt(404, json_encode(['status'=> 404, 'message' => 'Emoji not found']));
            } else {
                $result = json_encode($emoji);
                $response->body($result);
                return $response;
            }
        } catch(QueryException $e) {
            $app->halt(404, json_encode(['status'=> 404, 'message' => 'Emoji not found']));
        }
        return $response;
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

                return $response;
            }
            $result = json_encode($emojis);
            $response->body($result);
            return $response;
        } catch(Exception $e) {
            $response->body(json_encode(['message' => $e->getExceptionMessage()]));
            return $response;
        }
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

        $name = $app->request->params('name');
        $emojichar = $app->request->params('emojichar');
        $keywords = $app->request->params('keywords');
        $category = $app->request->params('category');

        $token = $app->request->headers('Authorization');
        $auth = Authorization::isAuthorised($app, $token);

        try {
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
        } catch(QueryException $e) {
            $app->halt(404, json_encode(['status'=> 404, 'message' => 'Emoji not found']));
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

        $name = $app->request->params('name');
        $emojichar = $app->request->params('emojichar');
        $keywords = $app->request->params('keywords');
        $category = $app->request->params('category');

        $token = $app->request->headers('Authorization');
        $auth = Authorization::isAuthorised($app, $token);

        try {
            $delete = Emoji::destroy($id);
            if ($delete == 1) {
                $response->body(json_encode(['status' => 200, 'message' => 'successfully deleted!']));
            } else {
                $response->body(json_encode(['status' => 404, 'message' => 'Emoji not found']));
            }
        } catch(QueryException $e) {
            $app->halt(404, json_encode(['status'=> 404, 'message' => 'Emoji not found']));
        }
        return $response;
    }
}

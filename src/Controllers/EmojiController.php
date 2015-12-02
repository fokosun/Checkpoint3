<?php

namespace Florence;

use Slim\Slim;
use Florence\User;
use Florence\Emoji;

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
        $createdby  = $app->request->params('createdby');

        if(! (isset($name) || isset($emojichar) || isset($keywords) || isset($category))) {
            $response->body(json_encode(['status' => 204,
                'message' => 'One or more credentials misspelled or missing!',
                'credentials' => ['name', 'emojichar','keywords', 'category'] ]));
        }
        return $response;
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
            $count = count($emojis);

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

        try {
            $emoji = Emoji::find($id);
            $count = count($emoji);

            if($count < 1) {
                $response->body(json_encode(['status' => 404, 'message' => 'Emoji not found']));

            } else {
                $result = json_encode($emoji);
                $response->body($result);
            }
        } catch(Exception $e) {
            $response->body(json_encode(['message' => $e->getExceptionMessage()]));
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
    * @return string
    */
    public static function delete(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');
        $passcode = Authorize::authentication($app);
        if ($passcode) {
            $deleted = Emoji::destroy($id);
            if ($deleted) {
                $info = [
                    "status"  => 400,
                    "message" => "Emoji $id deleted successfully!"
                ];
                return json_encode($info);
            } else {
                $info = [
                    "status"  => 404,
                    "message" => "Emoji does not exist!"
                ];
                return json_encode($info);
            }
        }
    }

    /**
    * @param $id
    * @param Slim $app
    * @return $response
    */
    public static function update(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');
        $passcode = Authorize::authentication($app);
        if ($passcode) {
            $update = Emoji::find($id);
            if ($update) {
                $columns = $app->request->isPut() ? $app->request->put() : $app->request->patch();
                foreach ($columns as $key => $value) {
                    $update->$key = $value;
                }
                $update->updated_at = gmdate("Y-m-d H:i:s", time());
                $update->save();
                return json_encode(['status' => 201, 'message' => 'Emoji '.$id.' successfully updated!']);
            } else {
                    return Errors::error401("The requested id:$id does not exist");
            }
        }
    }
}
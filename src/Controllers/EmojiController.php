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
        $app->response->headers->set('Content-Type', 'application/json');
        $name = $app->request->params(UserController::format('name'));
        $char = $app->request->params('char');
        $keywords = $app->request->params('keywords');
        $category = $app->request->params(UserController::format('category'));
        if (! isset($name)) {
            return Errors::error401('Insert a name');
        }
        if (! isset($char)) {
            return Errors::error401('Insert an emoji');
        }
        if (! isset($keywords)) {
            return Errors::error401('Insert keywords');
        }
        if (! isset($category)) {
            return Errors::error401('Insert a category');
        }
        $passcode = Authorize::authentication($app);
        if ($passcode) {
                $emoji = new Emoji;
                $emoji->name = $name;
                $emoji->char = $char;
                $emoji->keywords = $keywords;
                $emoji->category = $category;
                $emoji->created_by = Authorize::authentication($app);
                $emoji->save();
                return json_encode(['status' => 201, 'message' => 'Your emoji was successfully created.']);
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
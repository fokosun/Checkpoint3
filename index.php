<?php

namespace Florence;

require "vendor/autoload.php";

use Slim\Slim;
use Slim\Http\Response;

$app = new Slim([
    'templates.path' => 'templates/',
    'debug'          => true
]);


// Create user instance
$user = new User();

    //Auth routes
    $app->group('/auth', function () use ($app) {

        //create a new user
        $app->get('/register', function() use ($user) {
            // $user->getToken();
        });

        //login
        $app->post('/login', function() use ($user) {
            //AuthController->logIn();
        });

        //logout
        $app->get('auth/logout', function() use ($user) {
            //AuthController->logOut();
        });
    });

    // Emojis routes
    $app->group('/emojis', function () use ($app) {

        $app->get('/', function() use ($user, $app) {
            //EmojiController->getAll();
            // $data = json_decode($app->request->getBody());

            // $contentType = $app->response->headers->get('Content-Type');
            $res = new Response();
            $res->setStatus(400);
            $res->write('You made a bad request');
            $res->headers->set('Content-Type', 'text/plain');
            $array = $res->finalize();
            $app->render('register.html', ['array' => $array]);
            var_dump($array);
        });

        $app->get('/:id', function($id) use ($user) {
            //EmojiController->find($id);
        });

        $app->post('/', function() use ($user) {
            //EmojiController->create();
        });

        $app->put('/:id', function($id) use ($user) {
            //EmojiController->update($id);
        });

        $app->patch('/:id', function($id) use ($user) {
            //EmojiController->updatePartial($id);
        });

        $app->delete('/:id', function($id) use ($user) {
            //EmojiController->delete();
            // pass $id to EmojiController@delete($id)
        });

    });

$app->run();

// \Slim\Http\Request
// <?php
// // Returns instance of \Slim\Http\Request
// $request = $app->request;
<?php

/**
* Emoji API
* This script provides a RESTful API interface for Emojis
* Author: Florence Okosun
*/

namespace Florence;

require "vendor/autoload.php";

use Slim\Slim;
use Florence\UserMapper;
use Florence\AuthController;
use Florence\EmojiController;

$app = new Slim;
$authController = new AuthController($app);
$emojiController = new EmojiController($app);
$userMapper = new UserMapper($app);



    //Auth routes
    $app->group('/auth', function () use ($app) {

        //create a new user
        $app->get('/register', function() use ($authController) {
            return $authController->isThisUserAuthenticated();
        });

        //login
        $app->post('/login', function() use ($app) {
            //AuthController->logIn();
        });

        //logout
        $app->get('auth/logout', function() use ($app) {
            //AuthController->logOut();
        });
    });

    // Emojis routes
    $app->group('/emojis', function () use ($app) {

        $app->get('/', function() use ($emojiController, $app) {
            $emojiController->getAll();
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
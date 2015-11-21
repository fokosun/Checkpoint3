<?php

namespace Florence;

require "vendor/autoload.php";

use Slim\Slim;

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

        $app->get('/', function() use ($user) {
            //EmojiController->getAll();
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

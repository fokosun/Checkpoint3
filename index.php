<?php
/**
* Emoji API
* This script provides a RESTful API interface for Emojis
* Author: Florence Okosun
*/

require "vendor/autoload.php";
//require "src/connections/Connection.php";

use Slim\Slim;
use Florence\User;
use Florence\Emoji;
use Florence\AuthController;
use Florence\EmojiController;
use Illuminate\Database\Capsule\Manager as Capsule;

$app = new Slim([
    'templates.path' => 'templates/',
    'debug'          => true
]);

$app->get('/', function() use ($app) {
    $app->render('index.html');
});


// emojis routes group
$app->group('/emojis', function () use ($app) {

    /**
    * View all emojis
    */
    $app->get('/', function () use ($app) {
        return EmojiController::getAll($app);
    });

    /**
    * Find emoji by id
    */
    $app->post('/:id', function ($id) use ($app) {
        return EmojiController::find($app, $id);
    });

    /**
    * Create new emoji
    */
    $app->post('/', function () use ($app) {
        return EmojiController::create($app);
    });

    /**
    * Update an emoji
    */
    $app->put('/:id', function ($id) use ($app) {
        return EmojiController::update($app, $id);
    });

    $app->patch('/:id', function ($id) use ($app) {
        return EmojiController::update($app, $id);
    });

    /**
    * delete an emoji
    */
    $app->delete('/:id', function ($id) use ($app) {
        return EmojiController::delete($app, $id);
    });

    /**
    * extra (fetch emoji by any criteria)
    */
    $app->get('/:field/:criteria', function ($field, $criteria) use ($app) {
        return EmojiController::findBy($app, $field, $criteria);
    });

});

//registration route
$app->post('/register', function() use ($app) {
    return AuthController::register($app);
});

// Users' routes group
$app->group('/auth', function () use ($app) {

    /**
    * login
    */
    $app->post('/login', function () use ($app) {
        return AuthController::login($app);
    });

    /**
    * logout
    */
    $app->post('/logout', function () use ($app) {
        return AuthController::logout($app);
    });

});

$app->run();

// The end!! ☠ ☺

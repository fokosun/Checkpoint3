<?php
/**
* Emoji API
* This script provides a RESTful API interface for Emojis
* Author: It is I, Florence Okosun
*/

require "vendor/autoload.php";

use Slim\Slim;
use Florence\User;
use Florence\Emoji;
use Florence\EmojiController;

$app = new Slim([
    'templates.path' => 'templates/',
    'debug'          => true
]);

$app->get('/', function() use ($app) {
    $app->render('index.html');
});


// emojis routes
$app->group('/emojis', function () use ($app) {

    /**
    * View all emojis
    */
    $app->get('/', function () use ($app) {
        Emoji::getAll($app);
    });

    /**
    * Find emoji by id
    */
    $app->get('/:id', function ($id) use ($app) {
        Emoji::find($app, $id);
    });

    /**
    * Create new emoji
    */
    $app->post('/', function () use ($app) {
        Emoji::create($app);
    });

    /**
    * Update an emoji
    */
    $app->put('/:id', function ($id) use ($app) {
        Emoji::update($app, $id);
    });

    /**
    * Update an emoji
    */
    $app->delete('/:id', function ($id) use ($app) {
        Emoji::delete($app, $id);
    });

    /**
    * extra (fetch emoji by category)
    */
    $app->get('/:field/:criteria', function ($field, $criteria) use ($app) {
        Emoji::findBy($app, $field, $criteria);
    });

});


// Users' routes
$app->group('/auth', function () use ($app) {

    /**
    * login
    */
    $app->get('/login', function () use ($app) {
        User::login($app);
    });

    /**
    * logout
    */
    $app->get('/logout', function () use ($app) {
        User::logout($app);
    });

});

$app->post('/register', function() use ($app){
    // $auth = new AuthController();
    // $auth->registerUser($app);
    echo "come register";
});

$app->run();

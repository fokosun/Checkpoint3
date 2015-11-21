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

//create a new user
$app->get('/register', function() use ($user) {
   // $user->getToken();
});

$app->post('auth/login', function() use ($user) {
   //AuthController->logIn();
});

$app->get('auth/logout', function() use ($user) {
   //AuthController->logOut();
});

$app->get('/emojis', function() use ($user) {
   //EmojiController->getAll();
});

$app->get('/emojis/:id', function($id) use ($user) {
   //EmojiController->find($id);
});

$app->post('/emojis', function() use ($user) {
   //EmojiController->create();
});

$app->put('/emojis/:id', function($id) use ($user) {
   //EmojiController->update($id);
});

$app->patch('/emojis/:id', function($id) use ($user) {
   //EmojiController->updatePartial($id);
});

$app->delete('/emojis/:id', function($id) use ($user) {
   //EmojiController->delete();
});

$app->get('/', function() use($app)
    {
        $html = $app->request->headers->all();
        var_dump(json_encode($html));
        //this guy will load my home view
    });
$app->run();



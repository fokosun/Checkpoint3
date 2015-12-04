<?php

/**
* Created by Florence Okosun.
* Date: 11/1/2015
* Time: 11:31 AM
*/

use Florence\Config;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

Config::loadenv();

$capsule->addConnection([
    'driver'    => getenv('DRIVER'),
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_DATABASE'),
    'username'  => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
]);

$capsule->setAsGlobal();
//$capsule->bootEloquent();

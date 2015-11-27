<?php
/**
* Emoji API
* This script provides a RESTful API interface for Emojis
* Author: Florence Okosun
*/

namespace Florence;

use Slim\Slim;
use Exception;
use PDOException;
use Florence\User;
use Florence\Connection;

class AuthController
{

    public static function login(Slim $app)
    {
        echo "loggedin";
    }

    public static function logout(Slim $app)
    {
        echo "loggedout";
    }

}

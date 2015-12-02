<?php

namespace Florence;

use Dotenv\Dotenv;

class Config
{
    /**
    * use vlucas dotenv to access the .env file
    **/
   public static function loadenv()
   {
        If (! getenv('APP_ENV') == 'production') {
            $dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
            $dotenv->load();
        }
    }
}

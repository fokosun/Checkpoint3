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
        if(getenv('APP_ENV') !== 'production') {
            $dotenv = new Dotenv(__DIR__);
            $dotenv->load();
        }
    }
}

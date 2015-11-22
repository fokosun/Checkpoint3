<?php

namespace Florence;

use Slim\Middleware;

class AuthenticatorMiddleware extends Slim\Middleware
{
    public function call()
    {
        // Get reference to application
        $app = $this->app;

        // Capitalize response body
        $res = $app->response;
        $body = $res->getBody();
        $res->setBody(strtoupper($body));
    }
}

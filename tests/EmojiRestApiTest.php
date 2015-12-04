<?php

namespace Florence\Test;

use GuzzleHttp\Client;

class EmojiRestApiTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRoutes()
    {

        $client = new Client();
        $res = $client->request('GET', 'http://localhost:8080');

        $this->assertEquals('200', $res->getStatusCode());

    }


}

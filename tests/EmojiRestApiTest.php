<?php

namespace Florence\Test;

use Florence\Config;
use GuzzleHttp\Client;

class EmojiRestApiTest extends \PHPUnit_Framework_TestCase
{
    protected $domain;
    protected $client;
    protected $token;

    public function setUp() {

        Config::loadenv();

        $this->domain = 'http://emojis4devs.herokuapp.com';
        $this->client = new Client();
        $this->token = getenv('token');
    }

    public function testGetRoutes()
    {
        $res = $this->client->request('GET', $this->domain);
        $this->assertEquals('200', $res->getStatusCode());
    }
}

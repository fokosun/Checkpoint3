<?php

namespace Florence\Test;

use Florence\Emoji;
use Florence\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class EmojiRestApiTest extends \PHPUnit_Framework_TestCase
{
    protected $emoji;
    protected $client;
    protected $url;
    protected $token;

    public function setUp ()
    {
        Config::loadenv();

        $this->token = getenv('TEST_TOKEN');
        $this->username = getenv('TEST_USERNAME');
        $this->password = getenv('TEST_PASSWORD');
        $this->emoji = new Emoji();
        $this->client = new Client();
        $this->url = "http://emojis4devs.herokuapp.com";
    }

    /**
    * @expectedException GuzzleHttp\Exception\ClientException
    */
    public function testInvalidEndpoint()
    {
        $request = $this->client->request('GET', $this->url.'/auth/emojis');

        $this->assertInternalType('object' , $request);
        $this->assertEquals('200', $request->getStatusCode());
    }
}

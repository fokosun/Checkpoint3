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
    }

    /** protected route
    * tests registration endpoint
    */
    public function testPostRegister()
    {
        $register = $this->client->request('POST', $this->url.'/register');
        $content = $register->getHeader('content-type')[0];

        $this->assertEquals('200', $register->getStatusCode());
        $this->assertInternalType('object', $register->getBody());
        $this->assertEquals('application/json', $content);
    }

    /**
    * tests for all unprotected GET routes
    */
    public function testGetRoutes()
    {
        $index = $this->client->request('GET', $this->url);
        $emojis = $this->client->request('GET', $this->url.'/emojis');

        $contentTypeForIndex = $index->getHeader('content-type')[0];
        $contentTypeForEmojis = $emojis->getHeader('content-type')[0];

        $this->assertEquals('200', $index->getStatusCode());
        $this->assertInternalType('object', $index->getBody());

        $this->assertEquals('200', $emojis->getStatusCode());
        $this->assertInternalType('object', $emojis->getBody());

        $this->assertEquals('text/html;charset=UTF-8', $contentTypeForIndex);
        $this->assertEquals('application/json', $contentTypeForEmojis);

    }

    /**
    *
    */
    public function testFindEmojiById()
    {

    }


}

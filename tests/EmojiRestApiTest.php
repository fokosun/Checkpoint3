<?php

namespace Florence\Test;

use GuzzleHttp\Client;

class EmojiRestApiTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRoutes()
    {

        $client     = new Client();
        $index      = 'http://localhost:4190';

        $allEmojis  = 'http://localhost:4190/emojis';

        $index_req  =  $client->request('GET', $index);
        $all_req    =  $client->request('GET', $allEmojis);

        $this->assertEquals('200', $index_req->getStatusCode());
        $this->assertInternalType('object', $index_req->getBody());
        $this->assertEquals('200', $all_req->getStatusCode());
        $this->assertInternalType('object', $all_req->getBody());
    }

    public function testXoxo()
    {
        $client     = new Client();
        $url     = 'http://localhost:4190';
        try {
            $body = $client->request('GET', $url.'/register');
            $this->assertInternalType('string', $body);
        } catch (ClientException $e) {
            $test = 401;
            $status = '401 Unathorized';
        }
        $this->assertEquals('401', $test);
        $this->assertEquals('401 Unathorized', $status);
    }
}

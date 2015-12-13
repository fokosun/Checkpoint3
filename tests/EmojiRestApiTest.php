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
    protected $testId;

    public function setUp ()
    {
        Config::loadenv();

        $this->token = getenv('TEST_TOKEN');
        $this->username = getenv('TEST_USERNAME');
        $this->new_user = getenv('NEW_USER');
        $this->login_username = getenv('LOGIN_USERNAME');
        $this->login_password = getenv('LOGIN_PASSWORD');
        $this->password = getenv('TEST_PASSWORD');
        $this->testId = 3;
        $this->emoji = new Emoji();
        $this->client = new Client();
        $this->url = "http://emojis4devs.herokuapp.com";
        // $this->url = "http://localhost:8080";
    }

    /**
    * test register with registration params
    */
    public function testRegisterWithParams()
    {
        $data = [
            'username' => $this->new_user,
            'password' => $this->password
        ];
        $request = $this->client->request('POST', $this->url.'/register', ['form_params' => $data]);

        $this->assertInternalType('object' , $request);
        $this->assertEquals('200', $request->getStatusCode());
    }

    /**
    * @expectedException GuzzleHttp\Exception\ClientException
    */
    public function testRegisterWithoutParams()
    {
        $request = $this->client->request('POST', $this->url.'/register');

        $this->assertInternalType('object' , $request);
        $this->assertEquals('200', $request->getStatusCode());
    }

    /**
    * @expectedException GuzzleHttp\Exception\ClientException
    */
    public function testLoginWithoutCredentials()
    {
        $request = $this->client->request('POST', $this->url.'/auth/login');

        $this->assertInternalType('object' , $request);
        $this->assertEquals('200', $request->getStatusCode());
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

    /**
    * Test for unprotected Get Routes (Index and getAll)
    */
    public function testUnprotectedGetRoutes()
    {
        $index = $this->client->request('GET', $this->url);
        $getAll = $this->client->request('GET', $this->url);

        $_index = $index->getHeader('content-type')[0];
        $_getAll = $getAll->getHeader('content-type')[0];

        $this->assertEquals('200', $index->getStatusCode());
        $this->assertEquals('200', $getAll->getStatusCode());
        $this->assertInternalType('object', $index->getBody());
        $this->assertInternalType('object', $index->getBody());
        $this->assertEquals('text/html;charset=UTF-8', $_index);
        $this->assertEquals('text/html;charset=UTF-8', $_getAll);
    }

    /**
    * @expectedException GuzzleHttp\Exception\ClientException
    */
    public function testCreateWhenAuthorizationNotSet ()
    {
        $data = [
            'name' => 'TestEmojiName',
            'char' => '🎃',
            'keywords' => "apple, friut, mac",
            'category' => 'fruit'
        ];

        $request = $this->client->request('POST', $this->url.'/emojis', ['form_params' => $data]);
    }

    /**
    * @expectedException GuzzleHttp\Exception\ClientException
    */
    public function testUpdateWhenAuthorizationNotSet ()
    {
        $data = array(
            'name' => 'test'
        );
        $put = $this->client->request('PUT', $this->url.'/emojis', ['form_params' => $data]);
        $patch = $this->client->request('PATCH', $this->url.'/emojis', ['form_params' => $data]);
    }
}

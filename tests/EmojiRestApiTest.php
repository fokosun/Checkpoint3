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
        $this->password = getenv('TEST_PASSWORD');
        $this->new_user = getenv('NEW_USER');
        $this->testId = 3;
        $this->emoji = new Emoji();
        $this->client = new Client();
        $this->url = "http://emojis4devs.herokuapp.com";
    }

    /**
    * Test register with registration params
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
    * Test registration without params
    */
    public function testRegisterWithoutParams()
    {
        $request = $this->client->post($this->url.'/register', ['exceptions' => false]);
        $this->assertEquals('401', $request->getStatusCode());
    }

    /**
    * Test login without credentials
    */
    public function testLoginWithoutCredentials()
    {
        $request = $this->client->request('POST', $this->url.'/auth/login', ['exceptions' => false]);
        $this->assertEquals('401', $request->getStatusCode());
    }

    /**
    * Test invalid endpoints
    */
    public function testInvalidEndpoint()
    {
        $request = $this->client->request('GET', $this->url.'/auth/emojis', ['exceptions' => false]);
        $this->assertEquals('404', $request->getStatusCode());
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
    * Test create emoji without authorization
    */
    public function testCreateWithAuthorizationNotSet ()
    {
        $data = [
            'name' => 'test',
            'char' => '🎃',
            'keywords' => "apple, friut, mac",
            'category' => 'fruit'
        ];
        $request = $this->client->request('POST', $this->url.'/emojis',
            ['form_params' => $data, 'exceptions' => false]);

        $this->assertEquals('401', $request->getStatusCode());
    }

    /**
    * Test create emoji without params
    */
    public function testCreateWithoutEmojiParams ()
    {
        $request = $this->client->request('POST', $this->url.'/emojis',[ 'headers' =>
            ['Authorization'=> $this->token], 'exceptions' => false]);

        $this->assertEquals('401', $request->getStatusCode());
    }

    /**
    * Test create emoji with authorization set
    */
    public function testCreateWithAuthorizationSet()
    {
        $data = [
            'name' => 'test',
            'emojichar' => '🎃',
            'keywords' => "test, checkpoint, tia",
            'category' => 'test'
        ];

        $request = $this->client->request('POST', $this->url.'/emojis',[ 'headers' =>
            ['Authorization'=> $this->token],'form_params' => $data ]);

        $this->assertEquals('200', $request->getStatusCode());
    }

    /**
    * Test update emoji with authorization not set
    */
    public function testUpdateWithAuthorizationNotSet()
    {
        $data = array(
            'name' => 'test'
        );
        $put = $this->client->request('PUT', $this->url.'/emojis/' . $this->testId,
            ['form_params' => $data, 'exceptions' => false]);
        $patch = $this->client->request('PATCH', $this->url.'/emojis/' . $this->testId,
            ['form_params' => $data, 'exceptions' => false]);

        $this->assertEquals('401', $put->getStatusCode());
        $this->assertEquals('401', $patch->getStatusCode());
    }

    /**
    * Test update with authorization set
    */
    public function testUpdateWithAuthorizationSet()
    {
        $data = [
            'name' => 'test',
            'emojichar' => '🎃',
            'keywords' => "test, checkpoint, tia",
            'category' => 'test'
        ];

        $put = $this->client->request('PUT', $this->url.'/emojis/' . $this->testId,
            [ 'headers' =>['Authorization'=> $this->token],'form_params' => $data ]);

        $patch = $this->client->request('PATCH', $this->url.'/emojis/' . $this->testId,
            [ 'headers' =>['Authorization'=> $this->token],'form_params' => $data ]);

        $this->assertEquals('200', $put->getStatusCode());
        $this->assertEquals('200', $patch->getStatusCode());
    }

    /**
    * Test delete emoji with authorization not set
    */
    public function testDeleteWithAuthorizationNotSet()
    {
        $request = $this->client->request('DELETE', $this->url.'/emojis/' . $this->testId,
            ['exceptions' => false]);

        $this->assertEquals('401', $request->getStatusCode());
    }

    /**
    * Test delete with authorization set
    */
    public function testDeleteWithAuthorizationSet()
    {
        $delete = $this->client->request('DELETE', $this->url.'/emojis/' . $this->testId,
            [ 'headers' =>['Authorization'=> $this->token]]);

        $this->assertEquals('200', $delete->getStatusCode());
    }

    /**
    * Test login with login credentials set
    */
    public function testLoginWithCredentials()
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password
        ];
        $request = $this->client->request('POST', $this->url.'/auth/login', ['form_params' => $data]);

        $this->assertInternalType('object' , $request);
        $this->assertEquals('200', $request->getStatusCode());
    }

    /**
    * Test Logout
    */
    public function testLogout()
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password
        ];
        $request = $this->client->request('POST', $this->url.'/auth/logout',[ 'headers' =>
            ['Authorization'=> $this->token],'form_params' => $data ]);

        $this->assertInternalType('object' , $request);
        $this->assertEquals('200', $request->getStatusCode());
    }
}

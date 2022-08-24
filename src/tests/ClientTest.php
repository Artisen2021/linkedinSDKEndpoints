<?php

namespace Artisen2021\LinkedInSDK\tests;

use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\tests\Traits\TraitClient;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    use TraitClient;

    public function testAClientIsCreated()
    {
        $client = $this->getClient();
        $clientId = $client->getClientId();
        $clientSecret = $client->getClientSecret();

        $this->assertEquals($clientId,getenv('LINKEDIN_CLIENT_ID'));
        $this->assertEquals($clientSecret,getenv('LINKEDIN_CLIENT_SECRET'));
        $this->assertTrue($client instanceof Client);
    }

    public function testRedirectUrlIsRetrieved()
    {
        $client = $this->getClient();
        $_SERVER['HttpHOST'] = 'oauth.pstmn.io/v1/callback';
        $_SERVER['HTTPS'] = 'https';
        $redirectUrl = $client->getRedirectUrl();
        $this->assertEquals($redirectUrl,'https://oauth.pstmn.io/v1/callback/');
    }

    public function testLoginUrlIsRetrieved()
    {
        $client = $this->getClient();

        $loginUrl = $client->getLoginUrl();

        $this->assertEquals($loginUrl,'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.$this->client->getClientId().'&redirect_uri='.$this->client->getRedirectUrl().'&state='.$this->client->getState().'&scope='.implode('%20', Client::SCOPE));
    }
}
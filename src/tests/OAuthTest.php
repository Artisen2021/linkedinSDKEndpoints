<?php

namespace Artisen2021\LinkedInSDK\tests;

use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Http\OAuthCodeRequest;
use Artisen2021\LinkedInSDK\tests\Traits\TraitClient;
use Faker;
use Mockery;
use PHPUnit\Framework\TestCase;

class OAuthTest extends TestCase
{
    use TraitClient;

    public OAuthCodeRequest $oAuthCodeRequest;

    public function testOAuthCodeIsRetrieved()
    {
        $this->client = $this->getClient();
        $this->oAuthCodeRequest = new OAuthCodeRequest($this->client);
        $redirectUrl = 'https://oauth.pstmn.io/v1/callback/?code=1234';
        $oAuthCode = $this->oAuthCodeRequest->getCode($redirectUrl);
        $this->assertEquals('1234', $oAuthCode);
    }

    public function testOAuthCodeRequestIsSentAndOAuthCodeIsRetrieved()
    {
        $faker = Faker\Factory::create();
        $fakerCode = $faker->password;
        $this->client = $this->getClient();

        $OAuthCode = Mockery::mock(OAuthCodeRequest::class);
        $OAuthCode->shouldReceive('getOAuthCode')->shouldReceive('getCode')->andReturn($fakerCode);
        $code= $OAuthCode->getOAuthCode();
        $code = $OAuthCode->getCode($code);
        $this->assertEquals($code,$fakerCode);
    }
}
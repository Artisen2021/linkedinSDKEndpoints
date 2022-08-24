<?php

namespace Artisen2021\LinkedInSDK\tests;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Http\AccessTokenRequest;
use Artisen2021\LinkedInSDK\tests\Traits\TraitClient;
use Faker;
use Mockery;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    use TraitClient;

    public function testAccessTokenIsRetrieved()
    {
        $faker = Faker\Factory::create();
        $fakerToken = $faker->password;
        $fakerCode = $faker->password;
        $this->client = $this->getClient();

        $accessTokenRequest = Mockery::mock(AccessTokenRequest::class);
        $accessTokenRequest->shouldReceive('getAccessToken');

        $accessToken = Mockery::mock(AccessToken::class);
        $accessToken->shouldReceive('getToken')->andReturn($fakerToken);

        $accessTokenRequest->getAccessToken($fakerCode);
        $token = $accessToken->getToken();

        $this->assertEquals($token,$fakerToken);
    }
}
<?php

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Resources\CampaignGroupResources;
use Artisen2021\LinkedInSDK\Resources\ImageAdResources;
use Artisen2021\LinkedInSDK\Http\AccessTokenRequest;
use Artisen2021\LinkedInSDK\Http\AdRequest;
use Artisen2021\LinkedInSDK\Http\CampaignGroupRequest;
use Artisen2021\LinkedInSDK\Http\CampaignRequest;
use Artisen2021\LinkedInSDK\Http\ImageAdRequest;
use Artisen2021\LinkedInSDK\tests\Traits\TraitClient;
use PHPUnit\Framework\TestCase;

class AdTest extends TestCase
{
    use TraitClient;

    public Client $client;

/* @test */
    public function mockClientAccessTokenCampaignGroupAndCampaign()
    {
        $this->client = $this->getClient();
        $faker = Faker\Factory::create();
        $fakerCode = $faker->password;
        $fakerToken = $faker->password;

        $accessTokenRequest = Mockery::mock(AccessTokenRequest::class);
        $accessTokenRequest->shouldReceive('getAccessToken');

        $accessToken = Mockery::mock(AccessToken::class);
        $accessToken->shouldReceive('getToken')->andReturn($fakerToken);

        $accessTokenRequest->getAccessToken($fakerCode);

        $campaignGroupRequest = Mockery::mock(CampaignGroupRequest::class);
        $campaignGroupRequest->shouldReceive('create')->andReturn(CampaignGroupResources::class);

        $campaignRequest = Mockery::mock(CampaignRequest::class);
        $campaignRequest->shouldReceive('create');
    }

    public function testCreateImageAdIfMediaTypeIsEqualToImage()
    {
        $this->mockClientAccessTokenCampaignGroupAndCampaign();

        $adRequest = Mockery::mock(AdRequest::class);
        $adRequest->shouldReceive('create')->andReturn(ImageAdResources::class);

        $parameters = [
            'media_type' => 'image',
            'external_campaign_id' => '123',
            'external_account_id' => '456',
            'external_id' => '789',
        ];
        $ad = $adRequest->create($parameters);
        $this->assertEquals(ImageAdResources::class, $ad);
    }

    public function testAdIsDeleted()
    {
        $this->mockClientAccessTokenCampaignGroupAndCampaign();

        $adRequest = Mockery::mock(AdRequest::class);
        $adRequest->shouldReceive('create')->andReturn(ImageAdResources::class);

        $adImageRequest = Mockery::mock(ImageAdRequest::class);
        $adImageRequest->shouldReceive('create')->andReturn(ImageAdResources::class);

        $adImage = Mockery::mock(ImageAdResources::class);
        $adImage->shouldReceive('getExternalId')->andReturn(111112222);

        $adRequest->shouldReceive('delete');

        $adImage = Mockery::mock(ImageAdResources::class);
        $adImage->shouldReceive('getExternalId')->andReturn();

        $parameters = [
            'media_type' => 'image',
            'external_campaign_id' => '123',
            'external_account_id' => '456',
            'external_id' => '789',
        ];
        $adImageRequest->create($parameters);
        $adRequest->delete(111112222);
        $adIdResult = $adImage->getExternalId();

        $this->assertEquals(null, $adIdResult);
    }
}
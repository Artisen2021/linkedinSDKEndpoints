<?php

namespace Artisen2021\LinkedInSDK\tests\Traits;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Resources\CampaignGroupResources;
use Artisen2021\LinkedInSDK\Resources\ImageAdResources;
use Artisen2021\LinkedInSDK\Http\AccessTokenRequest;
use Artisen2021\LinkedInSDK\Http\AdRequest;
use Artisen2021\LinkedInSDK\Http\CampaignGroupRequest;
use Artisen2021\LinkedInSDK\Http\CampaignRequest;
use Faker;
use Mockery;

trait TraitRequests
{
    public Client $client;
    public AccessToken $accessToken;

    public function getClientAndAccessToken(): void
    {
        $this->client = $this->getClient();
        $faker = Faker\Factory::create();
        $fakerCode = $faker->password;
        $fakerToken = $faker->password;

        $accessTokenRequest = Mockery::mock(AccessTokenRequest::class);
        $accessTokenRequest->shouldReceive('getAccessToken');

        $accessToken = Mockery::mock(AccessToken::class);
        $accessToken->shouldReceive('getToken')->andReturn($fakerToken);

        $this->accessToken = $accessTokenRequest->getAccessToken($fakerCode);
    }

    public function getCampaignGroup(): void
    {
        $this->getClientAndAccessToken();

        $campaignGroupRequest = Mockery::mock(CampaignGroupRequest::class);
        $campaignGroupRequest->shouldReceive('create')->andReturn(CampaignGroupResources::class);

        $campaignGroup = Mockery::mock(CampaignGroupResources::class);
        $campaignGroup->shouldReceive('getExternalId')->andReturn(112233445);
    }

    public function getCampaignAndAdRequest(): void
    {
        $this->getCampaignGroup();

        $campaignRequest = Mockery::mock(CampaignRequest::class);
        $campaignRequest->shouldReceive('create');

        $adRequest = Mockery::mock(AdRequest::class);
        $adRequest->shouldReceive('create')->andReturn(ImageAdResources::class);
    }

}
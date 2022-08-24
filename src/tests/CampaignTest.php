<?php

namespace Artisen2021\LinkedInSDK\tests;

use Artisen2021\LinkedInSDK\Resources\CampaignResources;
use Artisen2021\LinkedInSDK\Http\CampaignRequest;
use Artisen2021\LinkedInSDK\tests\Traits\TraitClient;
use Artisen2021\LinkedInSDK\tests\Traits\TraitRequests;
use Mockery;
use PHPUnit\Framework\TestCase;

class CampaignTest extends TestCase
{
    use TraitClient;
    use TraitRequests;

    public array $dataCampaign = [
        'account_id' => '123456789',
        'campaign_group_id' => '112233445',
        'dailyBudget' => [
            'amount' => '18',
            'currencyCode' => 'EUR',
        ],
        'costType' => 'CPM',
        'country' => 'NL',
        'language' => 'nl',
        'name' => 'CampaignLiSDK',
        'start' => 1672531200000,
        'end' => 1672617600000,
        'locations' => [
            "urn:li:geo:103644278"
        ],
        'type' => 'SPONSORED_UPDATES',
        'unitCost' => [
            'amount' => '15',
            'currencyCode' => 'EUR',
        ],
        'status' => 'ACTIVE'
    ];

    public function testCampaignIsCreated()
    {
        $this->getCampaignGroup();

        $campaignRequest = Mockery::mock(CampaignRequest::class);
        $campaignRequest->shouldReceive('create')->andReturn(CampaignResources::class);

        $campaign = $campaignRequest->create($this->dataCampaign);

        $this->assertEquals(CampaignResources::class, $campaign);
    }

    public function testCampaignIdIsRetrieved()
    {
        $this->getCampaignGroup();

        $campaignRequest = Mockery::mock(CampaignRequest::class);
        $campaignRequest->shouldReceive('create');

        $campaign = Mockery::mock(CampaignResources::class);
        $campaign->shouldReceive('getExternalId')->andReturn(111222333);

        $campaignRequest->create($this->dataCampaign);
        $campaignResult = $campaign->getExternalId();

        $this->assertEquals(111222333, $campaignResult);
    }

    public function testCampaignIsDeleted()
    {
        $this->getCampaignGroup();

        $campaignRequest = Mockery::mock(CampaignRequest::class);
        $campaignRequest->shouldReceive('create');

        $campaignRequest->shouldReceive('delete');

        $campaign = Mockery::mock(CampaignResources::class);
        $campaign->shouldReceive('getStatus')->andReturn('ARCHIVED');

        $campaignRequest->create($this->dataCampaign);
        $campaignRequest->delete(111222333);
        $status = $campaign->getStatus();

        $this->assertEquals($status, 'ARCHIVED');
    }

    public function testCampaignIsUpdated()
    {
        $this->getCampaignGroup();

        $campaignRequest = Mockery::mock(CampaignRequest::class);
        $campaignRequest->shouldReceive('create');

        $campaign = Mockery::mock(CampaignResources::class);
        $campaign->shouldReceive('getExternalId')->andReturn(111222333);

        $campaignRequest->shouldReceive('update');

        $campaign->shouldReceive('getStatus')->andReturn('PAUSED');

        $campaignRequest->create($this->dataCampaign);

        $campaignRequest->update(111222333, [
            'status' => 'PAUSED'
        ]);
        $status = $campaign->getStatus();

        $this->assertEquals('PAUSED', $status);
    }
}


<?php

namespace Artisen2021\LinkedInSDK\tests;

use Artisen2021\LinkedInSDK\Resources\CampaignGroupResources;
use Artisen2021\LinkedInSDK\Http\CampaignGroupRequest;
use Artisen2021\LinkedInSDK\tests\Traits\TraitClient;
use Artisen2021\LinkedInSDK\tests\Traits\TraitRequests;
use Mockery;
use PHPUnit\Framework\TestCase;

class CampaignGroupTest extends TestCase
{
    use TraitClient;
    use TraitRequests;

    public function testCampaignGroupIsCreated()
    {
        $this->getClientAndAccessToken();

        $campaignGroupRequest = Mockery::mock(CampaignGroupRequest::class);
        $campaignGroupRequest->shouldReceive('create')->andReturn(CampaignGroupResources::class);

        $campaignGroup = $campaignGroupRequest->create([
            'account' => 'urn:li:sponsoredAccount:' . '123456789',
            'name' => 'Test CampaignResources Group',
            'runSchedule' => [
                'start' => '2020-01-01',
                'end' => '2020-12-31',
            ],
            'status' => 'ACTIVE',
            'totalBudget' => [
                'amount' => '100',
                'currencyCode' => 'USD'
            ]
        ]);

        $this->assertEquals(CampaignGroupResources::class, $campaignGroup);
    }

    public function testCampaignGroupIdIsRetrieved()
    {
        $this->getClientAndAccessToken();

        $campaignGroupRequest = Mockery::mock(CampaignGroupRequest::class);
        $campaignGroupRequest->shouldReceive('create');

        $campaignGroup = Mockery::mock(CampaignGroupResources::class);
        $campaignGroup->shouldReceive('getExternalId')->andReturn(112233445);

        $campaignGroupRequest->create([
            'account' => 'urn:li:sponsoredAccount:' . '123456789',
            'name' => 'Test CampaignResources Group',
            'runSchedule' => [
                'start' => '2020-01-01',
                'end' => '2020-12-31',
            ],
            'status' => 'ACTIVE',
            'totalBudget' => [
                'amount' => '100',
                'currencyCode' => 'USD'
            ]
        ]);
        $campaignGroupResult = $campaignGroup->getExternalId();

        $this->assertEquals(112233445, $campaignGroupResult);
    }

    public function testCampaignGroupIsDeleted()
    {
        $this->getClientAndAccessToken();

        $campaignGroupRequest = Mockery::mock(CampaignGroupRequest::class);
        $campaignGroupRequest->shouldReceive('create');

        $campaignGroupRequest->shouldReceive('delete');

        $campaignGroup = Mockery::mock(CampaignGroupResources::class);
        $campaignGroup->shouldReceive('getStatus')->andReturn('ARCHIVED');

        $campaignGroupRequest->create([
            'account' => 'urn:li:sponsoredAccount:' . '123456789',
            'name' => 'Test CampaignResources Group',
            'runSchedule' => [
                'start' => '2020-01-01',
                'end' => '2020-12-31',
            ],
            'status' => 'ACTIVE',
            'totalBudget' => [
                'amount' => '100',
                'currencyCode' => 'USD'
            ]
        ]);

        $campaignGroupRequest->delete(112233445);
        $status = $campaignGroup->getStatus();

        $this->assertEquals($status,'ARCHIVED');
    }

    public function testStatusCampaignGroupIsUpdated()
    {
        $this->getClientAndAccessToken();

        $campaignGroupRequest = Mockery::mock(CampaignGroupRequest::class);
        $campaignGroupRequest->shouldReceive('create');

        $campaignGroup = Mockery::mock(CampaignGroupResources::class);
        $campaignGroup->shouldReceive('getExternalId')->andReturn(112233445);

        $campaignGroupRequest->shouldReceive('update');

        $campaignGroup->shouldReceive('getStatus')->andReturn('PAUSED');

        $campaignGroupRequest->create([
            'account' => 'urn:li:sponsoredAccount:' . '123456789',
            'name' => 'Test CampaignResources Group',
            'runSchedule' => [
                'start' => '2020-01-01',
                'end' => '2020-12-31',
            ],
            'status' => 'ACTIVE',
            'totalBudget' => [
                'amount' => '100',
                'currencyCode' => 'USD'
            ]
        ]);
        $campaignGroupRequest->update(112233445, [
            'status' => 'PAUSED'
        ]);
        $campaignGroupStatus = $campaignGroup->getStatus();

        $this->assertEquals('PAUSED', $campaignGroupStatus);
    }

    public function testBudgetCampaignGroupIsUpdated()
    {
        $this->getClientAndAccessToken();

        $campaignGroupRequest = Mockery::mock(CampaignGroupRequest::class);
        $campaignGroupRequest->shouldReceive('create');

        $campaignGroup = Mockery::mock(CampaignGroupResources::class);
        $campaignGroup->shouldReceive('getExternalId')->andReturn(112233445);

        $campaignGroupRequest->shouldReceive('update');

        $campaignGroup->shouldReceive('getBudget')->andReturn(150);

        $campaignGroupRequest->create([
            'account' => 'urn:li:sponsoredAccount:' . '123456789',
            'name' => 'Test CampaignResources Group',
            'runSchedule' => [
                'start' => '2020-01-01',
                'end' => '2020-12-31',
            ],
            'status' => 'ACTIVE',
            'totalBudget' => [
                'amount' => '100',
                'currencyCode' => 'USD'
            ]
        ]);
        $campaignGroupRequest->update(112233445, [
            'totalBudget' => [
                'amount' => '150',
                'currencyCode' => 'EUR'
            ]
        ]);
        $campaignGroupBudget = $campaignGroup->getBudget();

        $this->assertEquals(150, $campaignGroupBudget);
    }
}
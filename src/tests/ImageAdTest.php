<?php

namespace Artisen2021\LinkedInSDK\tests;

use Artisen2021\LinkedInSDK\Resources\ImageAdResources;
use Artisen2021\LinkedInSDK\Http\AdRequest;
use Artisen2021\LinkedInSDK\tests\Traits\TraitClient;
use Artisen2021\LinkedInSDK\tests\Traits\TraitRequests;
use Mockery;
use PHPUnit\Framework\TestCase;

class ImageAdTest extends TestCase
{
    use TraitClient;
    use TraitRequests;

    public array $dataImageAd = [
        'account_id' => '123456789',
        'page_id' => '2',
        'campaign_id' => '112233445',
        'type' => 'image',
        'text' => 'Search a php developer',
        'title' => 'Job offer',
        'landing_page_url' => 'https://www.example.com/image.jpg',
        'media_url' => 'https://www.example.com/image.jpg',
        'call_to_action' => 'action',
    ];

    public function testImageAdIsCreated()
    {
        $this->getCampaignAndAdRequest();

        $imageAdRequest = Mockery::mock(AdRequest::class);
        $imageAdRequest->shouldReceive('create')->andReturn(ImageAdResources::class);

        $imageAd = $imageAdRequest->create($this->dataImageAd);

        $this->assertEquals($imageAd, ImageAdResources::class);
    }

    public function testImageAdIsDeleted()
    {
        $this->getCampaignAndAdRequest();

        $imageAdRequest = Mockery::mock(AdRequest::class);
        $imageAdRequest->shouldReceive('create');

        $imageAdRequest->shouldReceive('delete');

        $imageAd = Mockery::mock(ImageAdResources::class);
        $imageAd->shouldReceive('getStatus')->andReturn('PENDING_DELETION');

        $imageAdRequest->create($this->dataImageAd);
        $imageAdRequest->delete(111111111);
        $status = $imageAd->getStatus();

        $this->assertEquals($status,'PENDING_DELETION');
    }

    public function testImageAdIsUpdated()
    {
        $this->getCampaignAndAdRequest();

        $imageAdRequest = Mockery::mock(AdRequest::class);
        $imageAdRequest->shouldReceive('create');

        $imageAdRequest->shouldReceive('update');

        $imageAd = Mockery::mock(ImageAdResources::class);
        $imageAd->shouldReceive('getCallToAction')->andReturn('register');

        $imageAdRequest->update(111111111,[
            'account_id' => '509626541',
            'linkedin_page_id' => '81728855',
            'campaign_id' => '112233445',
            'media_type' => 'image',
            'message' => 'ImageAdLiSDK-Updated',
            'headline' => 'ImageAdLiSDK-Updated',
            'landing_page_url' => 'https://www.example.com/',
            'media_url' => 'https://customcodefactory.com/wp-content/uploads/2019/11/Laravel-logo.jpg',
            'call_to_action' => 'register'
        ]);

        $adCallToAction = $imageAd->getCallToAction();

        $this->assertEquals($adCallToAction,'register');
    }
}

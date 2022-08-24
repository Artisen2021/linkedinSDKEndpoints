<?php
declare(strict_types=1);

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Builder\AdBuilder;
use Artisen2021\LinkedInSDK\Resources\VideoAdResources;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateAd;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateImageAd;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateVideoAd;
//undefined classes?
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateDarkShare;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateDirectSponsoredContent;
use Artisen2021\LinkedInSDK\Exception\CouldNotUploadVideoAd;
use Artisen2021\LinkedInSDK\UrlEnums;
use GuzzleHttp\Exception\RequestException;

class VideoAdRequest extends LinkedInRequest
{
    public const HEADER_RESOURCE_ID = 'X-LinkedIn-Id';
    public Client $client;
    public VideoUploaderRequest $videoUploaderRequest;
    protected AdBuilder $builder;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->videoUploaderRequest = new VideoUploaderRequest($this->client);
        $this->builder = new AdBuilder();
    }

    /**
     * @throws CouldNotCreateVideoAd
     * @throws CouldNotUploadVideoAd
     */
    public function create(
        string $account_id,
        string $linkedin_page_id,
        int $campaign_id,
        string $message,
        string $headline,
        string $landing_page_url,
        string $media_url,
        string $call_to_action,
        string $token
    ): int
    {
        $mediaAsset = $this->videoUploaderRequest->uploadVideo($linkedin_page_id, $media_url, $token);

        $darkShare = $this->createDarkShare($linkedin_page_id, $message, $headline, $landing_page_url, $call_to_action, $mediaAsset, $token);

        $directShareId = $this->createDirectSponsoredContent($account_id, $linkedin_page_id, $landing_page_url, $darkShare['id'], $token);

        $requestBody = $this->builder->createVideoAdRequest($campaign_id, $directShareId);

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_CREATIVES'], []);

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotCreateVideoAd($e->getMessage(), $e->getCode());
        }
        $externalId = (int) $response->getHeaderLine(self::HEADER_RESOURCE_ID);

        if (empty($externalId)) {
            throw new CouldNotCreateVideoAd('Empty external id in response');
        }

        return $externalId;
    }

    private function createDarkShare(
        string $linkedin_page_id,
        string $message,
        string $headline,
        string $landing_page_url,
        string $call_to_action,
        string $mediaAsset,
        string $token
    ): array
    {
        $requestBody = $this->builder->createDarkShareForVideoAd($linkedin_page_id, $message, $headline, $landing_page_url, $mediaAsset, $call_to_action);

        $uri = rtrim($this->client->buildUrl(UrlEnums::URL['UGC_POST'],[]), '?');

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotCreateImageAd('LinkedIn : Failed to create a DarkShare for a video ad');
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    private function createDirectSponsoredContent(
        string $account_id,
        string $linkedin_page_id,
        string $headline,
        $ugcPostId,
        string $token
    ): string
    {
        $requestBody = $this->builder->createAdDirectSponsoredContent($account_id, $linkedin_page_id, $ugcPostId, $headline);

        $uri = $this->client->buildUrl(UrlEnums::URL['DIRECT_SPONSORED_POST'], []);

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotCreateImageAd('LinkedIn : Failed to create a direct sponsored content for a video ad');
        }

        $externalId = $response->getHeaderLine(self::HEADER_RESOURCE_ID);
        if (empty($externalId)) {
            throw new CouldNotCreateAd(
                'LinkedIn : Failed to create a direct sponsored content for a video ad because of empty external id'
            );
        }
        return $externalId;
    }
}

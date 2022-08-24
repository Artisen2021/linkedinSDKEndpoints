<?php
declare(strict_types=1);

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Builder\AdBuilder;
use Artisen2021\LinkedInSDK\Resources\ImageAdResources;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateImageAd;
use Artisen2021\LinkedInSDK\UrlEnums;
use GuzzleHttp\Exception\RequestException;

class ImageAdRequest extends LinkedInRequest
{
    public const HEADER_RESOURCE_ID = 'X-LinkedIn-Id';
    public Client $client;
    protected AdBuilder $builder;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->builder = new AdBuilder();
    }

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
        $darkShare = $this->createDarkShare($account_id, $linkedin_page_id, $campaign_id, $message, $headline, $landing_page_url, $media_url, $call_to_action, $token);

        $requestBody = $this->builder->createImageAdRequest($campaign_id, $darkShare['activity'], $darkShare['id']);

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_CREATIVES'], []);

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotCreateImageAd($e->getMessage(), $e->getCode());
        }
        $externalId = (int) $response->getHeaderLine(self::HEADER_RESOURCE_ID);

        if (empty($externalId)) {
            throw new CouldNotCreateImageAd('Empty external id in response');
        }
        return $externalId;
    }

    private function createDarkShare(
        string $account_id,
        string $linkedin_page_id,
        int $campaign_id,
        string $message,
        string $headline,
        string $landing_page_url,
        string $media_url,
        string $call_to_action,
        string $token
    ): array
    {
        $requestBody = $this->builder->createDarkShareForImageAd($account_id, $linkedin_page_id, $campaign_id, $message, $headline, $landing_page_url, $media_url, $call_to_action);

        $uri = $this->client->buildUrl(UrlEnums::URL['SHARES'], []);

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotCreateImageAd($e->getMessage(), $e->getCode());
        }
        return json_decode($response->getBody()->getContents(), true);
    }
}

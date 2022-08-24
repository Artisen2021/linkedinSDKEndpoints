<?php

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateAnalytics;
use Artisen2021\LinkedInSDK\UrlEnums;
use Exception;

class AnalyticsRequest extends LinkedInRequest
{
    public Client $client;
    public const CAMPAIGN_METRIC_FIELDS = [
        'clicks',
        'approximateUniqueImpressions',
        'impressions',
        'costInLocalCurrency',
        'externalWebsiteConversions',
        'pivotValue',
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchAnalytics(string $dateRange, string $granularity, int $campaignGroupId, string $token)
    {
        $url = sprintf(
            'q=analytics&pivot=CAMPAIGN_GROUP&%s&timeGranularity=%s&fields[0]=%s&campaignGroups[0]=urn:li:sponsoredCampaignGroup:%s',
            $dateRange,
            $granularity,
            implode(',',self::CAMPAIGN_METRIC_FIELDS),
            $campaignGroupId,
        );

        $uri = rtrim($this->client->buildUrl(UrlEnums::URL['AD_ANALYTICS'].'?'. $url,[]),'?');

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri, $header, []);
        } catch (Exception $e) {
            throw new CouldNotCreateAnalytics($e->getMessage(), $e->getCode());
        }
        return json_decode($response->getBody()->getContents(), true);
    }



}

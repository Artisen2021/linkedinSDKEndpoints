<?php
declare(strict_types=1);

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateCampaign;
use Artisen2021\LinkedInSDK\Exception\CouldNotDeleteCampaign;
use Artisen2021\LinkedInSDK\Exception\CouldNotUpdateCampaign;
use Artisen2021\LinkedInSDK\UrlEnums;
use GuzzleHttp\Exception\RequestException;
use Artisen2021\LinkedInSDK\Builder\CampaignBuilder;

class CampaignRequest extends LinkedInRequest
{
    public const HEADER_RESOURCE_ID = 'X-LinkedIn-Id';
    public Client $client;
    protected CampaignBuilder $campaignRequestBuilder;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->campaignRequestBuilder = new CampaignBuilder();
    }

    /**
     * @throws CouldNotCreateCampaign
     */
    public function create(
        string $account_id,
        int $campaignGroupId,
        string $costType,
        string $daily_amount,
        string $daily_currencyCode,
        string $country,
        string $language,
        string $name,
        int $start,
        int $end,
        string $locations,
        string $type,
        string $unicost_amount,
        string $unicost_currencyCode,
        string $status,
        string $token
    ): int
    {
        $requestBody = $this->campaignRequestBuilder->createRequestParams($account_id, $campaignGroupId,$costType,$daily_amount,$daily_currencyCode,$country,$language,$name,$start,$end,$locations,$type,$unicost_amount,$unicost_currencyCode,$status);

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_CAMPAIGNS'], []);

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotCreateCampaign($e->getMessage(), $e->getCode());
        }
        $externalId = (int) $response->getHeaderLine(self::HEADER_RESOURCE_ID);

        if (empty($externalId)) {
            throw new CouldNotCreateCampaign('Empty external id in response');
        }
        return $externalId;
    }

    /**
     * @throws CouldNotDeleteCampaign
     */
    public function delete(int $campaignId, string $token)
    {
        $requestBody = [
            'patch' => [
                '$set' => [
                    'status' => 'ARCHIVED',
                ],
            ],
        ];

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_CAMPAIGNS'].'/'.$campaignId, []);

        $header = $this->client->getHeader($token);

        try {
            (new LinkedInRequest())->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotDeleteCampaign($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws CouldNotUpdateCampaign
     */
    public function update(int $campaignId, string $status, string $token): void
    {
        $requestBody = [
            'patch' => [
                '$set' => [
                    'status' => $status
                ],
            ],
        ];

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_CAMPAIGNS'].'/'.$campaignId, []);

        $header = $this->client->getHeader($token);

        try {
            (new LinkedInRequest())->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotUpdateCampaign($e->getMessage(), $e->getCode());
        }
    }
}



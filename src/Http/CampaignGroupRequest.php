<?php
declare(strict_types=1);

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateCampaignGroup;
use Artisen2021\LinkedInSDK\Exception\CouldNotDeleteCampaignGroup;
use Artisen2021\LinkedInSDK\Exception\CouldNotUpdateCampaignGroup;
use Artisen2021\LinkedInSDK\UrlEnums;
use GuzzleHttp\Exception\RequestException;

class CampaignGroupRequest extends LinkedInRequest
{
    public const HEADER_RESOURCE_ID = 'X-LinkedIn-Id';
    public Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws CouldNotCreateCampaignGroup
     */
    public function create(
        string $account_id,
        string $name,
        int $start,
        int $end,
        string $total_budget,
        string $currency,
        string $token
    ): int
    {
        $requestBody = [
            'account' => 'urn:li:sponsoredAccount:' . $account_id,
            'name' => $name,
            'runSchedule' => [
                'start' => $start,
                'end' => $end,
            ],
            'status' => 'ACTIVE',
            'totalBudget' => [
                'amount' => $total_budget,
                'currencyCode' => $currency,
            ],
        ];

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_CAMPAIGN_GROUPS'], []);

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotCreateCampaignGroup($e->getMessage(), $e->getCode());
        }
        $externalId = (int) $response->getHeaderLine(self::HEADER_RESOURCE_ID);

        if (empty($externalId)) {
            throw new CouldNotCreateCampaignGroup('Empty external id in response');
        }
        return $externalId;
    }

    /**
     * @throws CouldNotDeleteCampaignGroup
     */
    public function delete(int $campaignGroupId, string $token): void
    {
        $requestBody = [
            'patch' => [
                '$set' => [
                    'status' => 'ARCHIVED',
                ],
            ],
        ];

        $uri = rtrim($this->client->buildUrl(UrlEnums::URL['AD_CAMPAIGN_GROUPS'].'/'.$campaignGroupId, []),'?');

        $header = $this->client->getHeader($token);

        try {
            (new LinkedInRequest())->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotDeleteCampaignGroup($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws CouldNotUpdateCampaignGroup
     */
    public function update(int $campaignGroupId,  ?string $status, ?string $budget, string $token): void
    {
        $requestBody = [
            'patch' => [
                '$set' => isset($status)
                    ? ['status' => $status]
                    : ['totalBudget' => [
                        'amount' => $budget,
                        'currencyCode' => 'EUR'
                    ]
                    ],
            ],
        ];

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_CAMPAIGN_GROUPS'].'/'.$campaignGroupId, []);

        $header = $this->client->getHeader($token);

        try {
            (new LinkedInRequest())->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotUpdateCampaignGroup($e->getMessage(), $e->getCode());
        }
    }

}
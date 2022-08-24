<?php

namespace Artisen2021\LinkedInSDK\Builder;

class CampaignBuilder
{
    public function createRequestParams(
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
        string $status
    ): array
    {
        return [
            'account' => 'urn:li:sponsoredAccount:' . $account_id,
            'campaignGroup' => 'urn:li:sponsoredCampaignGroup:' . $campaignGroupId,
            'costType' => $costType,
            'dailyBudget' => [
                'amount' => $daily_amount,
                'currencyCode' => $daily_currencyCode,
            ],
            'locale' => [
                'country' => $country,
                'language' => $language,
            ],
            'name' => $name,
            'runSchedule' => [
                'start' => $start,
                'end' => $end,
            ],
            'targetingCriteria' => [
                'include' => [
                    'and' => [
                        [
                            'or' => [
                                'urn:li:adTargetingFacet:locations' => [
                                    $locations
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'type' => $type,
            'unitCost' => [
                'amount' => $unicost_amount,
                'currencyCode' => $unicost_currencyCode,
            ],
            'status' => $status
        ];
    }

}
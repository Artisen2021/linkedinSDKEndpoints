<?php
declare(strict_types=1);


namespace Artisen2021\LinkedInSDK\Builder;

class AdBuilder
{

    private const OWNER = 'urn:li:organization:';

    //https://docs.microsoft.com/en-us/linkedin/marketing/integrations/community-management/shares/share-api?view=li-lms-unversioned&tabs=http#direct-sponsored-content-share
    public function createDarkShareForImageAd(
        string $account_id,
        string $linkedin_page_id,
        int $campaign_id,
        string $message,
        string $headline,
        string $landing_page_url,
        string $media_url,
        string $call_to_action
    ): array
    {
        return [
            'agent' => 'urn:li:sponsoredAccount:' . $account_id,
            'content' => [
                'contentEntities' => [
                    [
                        'landingPageTitle' => strtoupper(str_replace(' ', '_', $call_to_action)),
                        'landingPageUrl' => (string)$campaign_id,
                        'description' => $message,
                        'title' => $headline,
                        'entityLocation' => $landing_page_url,
                        'thumbnails' => [
                            [
                                'resolvedUrl' => $media_url,
                            ],
                        ],
                    ],
                ],
            ],
            'owner' => self::OWNER . $linkedin_page_id,
            'subject' => $headline,
            'text' => [
                'text' => $message,
            ],
        ];
    }

    public function createImageAdRequest(int $campaign_id, string $share_activity, string $share_reference): array
    {
        return [
            'campaign' => 'urn:li:sponsoredCampaign:' . $campaign_id,
            'reference' => 'urn:li:share:' . $share_reference,
            'status' => 'ACTIVE',
            'type' => 'SPONSORED_STATUS_UPDATE',
            'variables' => [
                'data' => [
                    'com.linkedin.ads.SponsoredUpdateCreativeVariables' => [
                        'activity' => $share_activity,
                    ],
                ],
            ],
        ];
    }

    //https://docs.microsoft.com/en-us/linkedin/marketing/integrations/community-management/shares/vector-asset-api?view=li-lms-unversioned&tabs=http
    public function requestCredentialsForVideoUpload(string $pageId): array
    {
        return [
            'registerUploadRequest' => [
                'owner' => self::OWNER . $pageId,
                'recipes' => [
                    'urn:li:digitalmediaRecipe:ads-video',
                ],
                'serviceRelationships' => [
                    [
                        'identifier' => 'urn:li:userGeneratedContent',
                        'relationshipType' => 'OWNER',
                    ],
                ],
            ],
        ];
    }


    public function createDarkShareForVideoAd(string $linkedin_page_id, string $message, string $headline, string $landing_page_url, string $mediaAsset, string $call_to_action): array
    {
        return [
            'author' => self::OWNER . $linkedin_page_id,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'media' => [
                        [
                            'landingPage' => [
                                'landingPageTitle' => strtoupper(str_replace(' ', '_', $call_to_action)),
                                'landingPageUrl' => $landing_page_url,
                            ],
                            'media' => $mediaAsset,
                            'status' => 'READY',
                            'title' => [
                                'text' => $headline,
                            ],
                        ],
                    ],
                    'shareCommentary' => [
                        'text' => $message,
                    ],
                    'shareMediaCategory' => 'VIDEO',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.SponsoredContentVisibility' => 'DARK',
            ],
        ];
    }

    public function createAdDirectSponsoredContent(string $account_id, string $linkedin_page_id, $ugcPostId, string $headline): array
    {
        return [
            'account' => 'urn:li:sponsoredAccount:' . $account_id,
            'contentReference' => $ugcPostId,
            'name' => $headline,
            'owner' => self::OWNER . $linkedin_page_id,
            'type' => 'VIDEO',
        ];
    }

    public function createVideoAdRequest(int $campaign_id, string $directShareId): array
    {
        return [
            'campaign' => 'urn:li:sponsoredCampaign:' . $campaign_id,
            'reference' => $directShareId,
            'status' => 'ACTIVE',
            'type' => 'SPONSORED_VIDEO',
        ];
    }

    public function delete()
    {
        return [
            'patch' => [
                '$set' => [
                    'status' => 'PENDING_DELETION',
                ]
            ]
        ];
    }

}

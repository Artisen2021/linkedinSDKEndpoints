<?php

namespace Artisen2021\LinkedInSDK;

class UrlEnums
{
    const URL = [
        'AD_CAMPAIGN_GROUPS' => 'adCampaignGroupsV2',
        'AD_CAMPAIGNS' => 'adCampaignsV2',
        'AD_TARGETING_ENTITIES' => 'adTargetingEntities',
        'AD_CREATIVES' => 'adCreativesV2',
        'ASSET_REGISTER' => 'assets?action=registerUpload',
        'SHARES' => 'shares',
        'UGC_POST' => 'ugcPosts',
        'DIRECT_SPONSORED_POST' => 'adDirectSponsoredContents',
        'PENDING_CLIENT_PAGES' => 'organizationAcls?q=roleAssignee&state=REQUESTED&count=5000',
        'AD_ANALYTICS' => 'adAnalyticsV2',
    ];
}

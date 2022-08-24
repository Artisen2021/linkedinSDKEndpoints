<?php
declare(strict_types=1);

namespace Artisen2021\LinkedInSDK\Resources;

use Artisen2021\LinkedInSDK\Exception\CouldNotCreateCampaign;
use Exception;


//TODO: This class has way too many return points, gotta refactor
class CampaignResources
{
    protected int $linkedInAccountId;
    protected int $campaign_group_id;
    protected string $name;
    protected int $start;
    protected int $end;
    protected string $total_budget;
    protected string $daily_budget;
    protected string $currency;
    protected string $country;
    protected string $locations;
    protected string $profiles;
    protected string $language;
    protected string $objective;
    protected string $campaign_ad_type;
    protected string $skills;
    protected int $externalId;
    protected string $status;

    public function getExternalId(): int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getLinkedInAccountId(): int
    {
        return $this->linkedInAccountId;
    }

    public function getCampaignGroupId(): int
    {
        return $this->campaign_group_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    //TODO: Instead of returning dates as integers, may be good to use carbon object?
    public function getStart(): int
    {
        return $this->start;
    }

    public function setStart($start): void
    {
        $this->start = $start;
    }

    public function getEnd(): int
    {
        return $this->end;
    }

    public function setEnd($end): void
    {
        $this->end = $end;
    }

    public function getTotalBudget(): string
    {
        return $this->total_budget;
    }

    public function setTotalBudget($total_budget): void
    {
        $this->total_budget = $total_budget;
    }

    public function getDailyBudget(): string
    {
        return $this->daily_budget;
    }

    public function setDailyBudget($daily_budget): void
    {
        $this->daily_budget = $daily_budget;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getLocations(): string
    {
        return $this->locations;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage($language): void
    {
        $this->language = $language;
    }

    public function getObjective(): string
    {
        return $this->objective;
    }

    public function setObjective($objective): void
    {
        $this->objective = $objective;
    }

    public function getCampaignAdType(): string
    {
        return $this->campaign_ad_type;
    }

    public function setCampaignAdType($campaign_ad_type): void
    {
        $this->campaign_ad_type = $campaign_ad_type;
    }

    public function getSkills(): string
    {
        return $this->skills;
    }

    public function setSkills($skills): void
    {
        $this->skills = $skills;
    }

    public function getProfiles(): string
    {
        return $this->profiles;
    }

    public function setProfiles($profiles): void
    {
        $this->profiles = $profiles;
    }
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }
}
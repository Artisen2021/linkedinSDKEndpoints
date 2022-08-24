<?php
declare(strict_types=1);

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Resources\AdResources;
use Artisen2021\LinkedInSDK\Resources\ImageAdResources;
use Artisen2021\LinkedInSDK\Resources\VideoAdResources;
use Artisen2021\LinkedInSDK\Exception\CouldNotCreateAd;
use Artisen2021\LinkedInSDK\Exception\CouldNotDeleteAd;
use Artisen2021\LinkedInSDK\UrlEnums;
use GuzzleHttp\Exception\RequestException;
use Artisen2021\LinkedInSDK\Builder\AdBuilder;

class AdRequest extends LinkedInRequest
{
    public Client $client;
    protected const MEDIA_TYPE_IMAGE = 'image';
    protected const MEDIA_TYPE_VIDEO = 'video';
    public AdBuilder $builder;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->builder = new AdBuilder();
    }

    public function create(
        string $account_id,
        string $linkedin_page_id,
        int $campaign_id,
        string $media_type,
        string $message,
        string $headline,
        string $landing_page_url,
        string $media_url,
        string $call_to_action,
        string $token
    )
    {
        if ($media_type === self::MEDIA_TYPE_IMAGE) {
            return (new ImageAdRequest($this->client))->create($account_id, $linkedin_page_id, $campaign_id, $message, $headline, $landing_page_url, $media_url, $call_to_action, $token);
        }

        if ($media_type === self::MEDIA_TYPE_VIDEO) {
            return (new VideoAdRequest($this->client))->create($account_id, $linkedin_page_id, $campaign_id, $message, $headline, $landing_page_url, $media_url, $call_to_action, $token);
        }
        throw new CouldNotCreateAd('LinkedIn : Failed to create an ad');
    }

    /**
     * @throws CouldNotDeleteAd
     */
    public function delete(int $adId, string $token)
    {
        $requestBody = $this->builder->delete();

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_CREATIVES']. '/' . $adId,[]);
        $header = $this->client->getHeader($token);
        try {
            (new LinkedInRequest())->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotDeleteAd($e->getMessage(), $e->getCode(), ['ad_id' => $adId]);
        }
    }

    /**
     * @throws CouldNotDeleteAd
     */
    public function update(
        int $adId,
        string $account_id,
        string $linkedin_page_id,
        int $campaign_id,
        string $media_type,
        string $message,
        string $headline,
        string $landing_page_url,
        string $media_url,
        string $call_to_action,
        string $token)
    {
        $this->delete($adId, $token);

        return $this->create($account_id, $linkedin_page_id, $campaign_id, $media_type, $message, $headline, $landing_page_url, $media_url, $call_to_action, $token);
    }

}

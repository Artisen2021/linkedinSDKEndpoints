<?php

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Builder\AdBuilder;
use Artisen2021\LinkedInSDK\Exception\CouldNotUploadVideoAd;
use Artisen2021\LinkedInSDK\UrlEnums;
use GuzzleHttp\Exception\RequestException;

class VideoUploaderRequest extends LinkedInRequest
{
    public Client $client;
    public AdBuilder $builder;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->builder = new AdBuilder();
    }

    /**
     * @throws CouldNotUploadVideoAd
     */
    public function uploadVideo(
        string $linkedin_page_id,
        string $media_url,
        string $token
    ): string
    {
        $uploadRequest = $this->requestCredentialsForVideoUpload($linkedin_page_id, $token);

        $uploadUrl = $uploadRequest['value']
        ['uploadMechanism']
        ['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']
        ['uploadUrl'];
        $uploadHeaders = $uploadRequest['value']
        ['uploadMechanism']
        ['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']
        ['headers'];

        $mediaAsset = $uploadRequest['value']['asset'];

        try {
            (new LinkedInRequest())
                ->send('PUT', $uploadUrl, $uploadHeaders, file_get_contents($media_url));
        } catch (RequestException $e) {
            throw new CouldNotUploadVideoAd('LinkedIn : Failed to upload video');
        }
        return $mediaAsset;
    }

    private function requestCredentialsForVideoUpload(string $pageId, string $token): array
    {
        $requestBody = $this->builder->requestCredentialsForVideoUpload($pageId);

        $header = $this->client->getHeader($token);

        $uri = rtrim($this->client->buildUrl(UrlEnums::URL['ASSET_REGISTER'],[]), '?');

        try {
            $request = new LinkedInRequest();
            $response = $request->send('POST', $uri, $header, $requestBody);
        } catch (RequestException $e) {
            throw new CouldNotUploadVideoAd('LinkedIn : Failed to request credentials for video upload');
        }
        return json_decode($response->getBody()->getContents(), true);
    }
}

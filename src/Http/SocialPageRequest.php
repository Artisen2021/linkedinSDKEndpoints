<?php

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Event\LinkedInGetPageCurrentStatusEvent;
use Artisen2021\LinkedInSDK\Event\LinkedInGetPageDataEvent;
use Artisen2021\LinkedInSDK\Event\LinkedInRequestAdAccountsEvent;
use Artisen2021\LinkedInSDK\Event\LinkedInRequestPendingPagesEvent;
use Artisen2021\LinkedInSDK\Exception\CouldNotGetPageDataEvent;
use Artisen2021\LinkedInSDK\Exception\CouldNotGetPendingClientPages;
use Artisen2021\LinkedInSDK\Exception\PermissionCouldNotBeCreated;
use Artisen2021\LinkedInSDK\UrlEnums;
use JsonException;

class SocialPageRequest extends LinkedInRequest
{
    public Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws CouldNotGetPendingClientPages
     */
    public function getPendingClientPages(LinkedInRequestPendingPagesEvent $event, string $token): ?array
    {
        $uri = rtrim($this->client->buildUrl(UrlEnums::URL['PENDING_CLIENT_PAGES'], []), '?');

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri, $header, []);
        } catch (PermissionCouldNotBeCreated $e) {
            throw new CouldNotGetPendingClientPages($e->getMessage(), $e->getCode());
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws CouldNotGetPageDataEvent
     * @throws JsonException
     */
    public function getPageData(LinkedInGetPageDataEvent $event, string $token): ?array
    {
        $queryFields = [
            'primaryOrganizationType',
            'vanityName',
            'localizedName',
            'logoV2(cropped~:playableStreams,cropInfo)'
        ];
        $url = sprintf(
            'organizations/%s?projection=(%s)',
            $event->getPageId(),
            implode(',', $queryFields)
        );
        $uri = rtrim($this->client->buildUrl($url, []), '?');

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri, $header, []);
        } catch (PermissionCouldNotBeCreated $e) {
            throw new CouldNotGetPageDataEvent($e->getMessage(), $e->getCode());
        }
        return json_decode($response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * @throws CouldNotGetPageDataEvent
     * @throws JsonException
     */
    public function getPageCurrentStatus(LinkedInGetPageCurrentStatusEvent $event, string $token): ?array
    {
        $organizationUrn = sprintf('urn:li:organization:%s',$event->getPageId());
        $url = sprintf(
            'organizationAcls?q=organization&organization=%s&role=ADMINISTRATOR&state=APPROVED',
            $organizationUrn
        );
        $uri = rtrim($this->client->buildUrl($url, []), '?');

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri, $header, []);
        } catch (PermissionCouldNotBeCreated $e) {
            throw new CouldNotGetPageDataEvent($e->getMessage(), $e->getCode());
        }
        return json_decode($response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * @throws CouldNotGetPageDataEvent
     * @throws JsonException
     */
    public function searchAdAccountsByPageId(LinkedInRequestAdAccountsEvent $event, string $token): ?array
    {
        $organisationUrn = sprintf('urn:li:organization:%s', $event->getPageId());
        $url = sprintf('adAccountsV2?q=search&search.reference.values[0]=%s', $organisationUrn);
        $uri = rtrim($this->client->buildUrl($url, []), '?');

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri, $header, []);
        } catch (PermissionCouldNotBeCreated $e) {
            throw new CouldNotGetPageDataEvent($e->getMessage(), $e->getCode());
        }
        return json_decode($response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}

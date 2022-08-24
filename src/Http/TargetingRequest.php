<?php
declare(strict_types=1);

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Exception\CouldNotFetchLocation;
use Artisen2021\LinkedInSDK\Exception\CouldNotFetchSimilar;
use Artisen2021\LinkedInSDK\Exception\CouldNotFetchUrns;
use Artisen2021\LinkedInSDK\UrlEnums;
use GuzzleHttp\Exception\RequestException;

class TargetingRequest extends LinkedInRequest
{
    public const HEADER_RESOURCE_ID = 'X-LinkedIn-Id';
    public Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    //https://docs.microsoft.com/en-us/linkedin/marketing/integrations/ads/advertising-targeting/ads-targeting?view=li-lms-2022-06&tabs=http#ad-targeting-entities

    /**
     * @throws CouldNotFetchLocation
     */
    public function fetchLocation(string $locationName, string $token): string
    {
        $queryString =
            'q=TYPEAHEAD' .
            '&facet=urn:li:adTargetingFacet:locations' .
            '&query=' . $locationName;

        $uri = rtrim($this->client->buildUrl(UrlEnums::URL['AD_TARGETING_ENTITIES']. '?' . $queryString, []),'?');

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri, $header,[]);
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new CouldNotFetchLocation($e->getMessage(), $e->getCode());
        }
        return empty($body['elements']) ? '' : $body['elements'][0]['urn'];
    }

    /**
     * @throws CouldNotFetchUrns
     */
    public function fetchUrns(string $query, string $facet, string $token, $exact = false): array
    {
        $urns = [];
        $queryString =
            'q=TYPEAHEAD' .
            '&facet=urn:li:adTargetingFacet:' . $facet .
            '&query=' . $query;

        $uri = $this->client->buildUrl(UrlEnums::URL['AD_TARGETING_ENTITIES']. '?' . $queryString, []);

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri, $header, []);
            $body = json_decode($response->getBody()->getContents(), true);
            if ($exact && !empty($body['elements'])) {
                $urns[] = $body['elements'][0]['urn'];
            } else {
                foreach ($body['elements'] as $element) {
                    $urns[] = $element['urn'];
                }
            }
        } catch (RequestException $e) {
            throw new CouldNotFetchUrns($e->getMessage(), $e->getCode());
        }
        return $urns;
    }

    /**
     * @throws CouldNotFetchSimilar
     */
    public function fetchSimilar(string $urn, string $facet, string $token): array
    {
        $titles = [];
        $queryString =
            'q=similarEntities' .
            '&facet=urn:li:adTargetingFacet:' . $facet .
            '&entities=' . $urn;
        
        $uri = $this->client->buildUrl(UrlEnums::URL['AD_TARGETING_ENTITIES']. '?' . $queryString, []);

        $header = $this->client->getHeader($token);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri, $header, []);
            $body = json_decode($response->getBody()->getContents(), true);
            foreach ($body['elements'] as $element) {
                $titles[] = $element['urn'];
            }
        } catch (RequestException $e) {
            throw new CouldNotFetchSimilar($e->getMessage(), $e->getCode());
        }
        return $titles;
    }


}

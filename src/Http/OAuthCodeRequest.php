<?php

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\Client;
use Artisen2021\LinkedInSDK\Authentication\Scope;
use Artisen2021\LinkedInSDK\Exception\CouldNotGetOAuthCode;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class OAuthCodeRequest
{
    protected Client $client;
    public const SCOPE = [Scope::READ_LITE_PROFILE, Scope::SHARE_AS_USER];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getOAuthCode()
    {
        $params = [
            'response_type' => $this->client::OAUTH2_RESPONSE_TYPE,
            'client_id' => $this->client->getClientId(),
            'redirect_uri' => $this->client->getRedirectUrl(),
            'state' => $this->client->getState(),
            'scope' => implode('%20', self::SCOPE),
        ];
        $uri = $this->client->buildUrl('authorization',$params);

        try {
            $request = new LinkedInRequest();
            $response = $request->send('GET', $uri,[],[]);
        } catch (Exception $e) {
            throw new CouldNotGetOAuthCode($e->getMessage(), $e->getCode());
        }
        return $response;
    }

    public function getCode($response): string
    {
        $url_components = parse_url($response);

        parse_str($url_components['query'], $params);

        return $params['code'];
    }
}
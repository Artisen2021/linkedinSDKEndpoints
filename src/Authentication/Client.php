<?php

namespace Artisen2021\LinkedInSDK\Authentication;

use Artisen2021\LinkedInSDK\Exception\CouldNotCreateClient;
use Exception;

class Client
{
    private const OAUTH2_API_ROOT = 'https://api.linkedin.com/v2/';

    public string $clientId;

    public string $clientSecret;

    public string $redirectUrl;

    public string $state;

    public const OAUTH2_RESPONSE_TYPE = 'code';

    public const SCOPE = [Scope::READ_LITE_PROFILE, Scope::SHARE_AS_USER];


    public function __construct(string $clientId, string $clientSecret)
    {
        var_dump('the code went passed this point');
        try{
            $this->clientId = $clientId;
            $this->clientSecret = $clientSecret;
        }catch(Exception $e){
            throw new CouldNotCreateClient($e->getMessage(), $e->getCode());
        }
    }

    public function getHeader(string $token): array
    {
        return [
            'Authorization' => 'Bearer ' . $token,
            'Content-type' => 'application/json'
        ];
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getState() :string
    {
        if (empty($this->state)) {
            $this->setState(
                rtrim(
                    base64_encode(uniqid('', true)),
                    '='
                )
            );
        }
        return $this->state;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getRedirectUrl(): string
    {
        if (empty($this->redirectUrl)) {
            $this->setRedirectUrl($this->getCurrentUrl());
        }
        return $this->redirectUrl;
    }

    public function setRedirectUrl($redirectUrl): void
    {
        $redirectUrl = filter_var($redirectUrl, FILTER_VALIDATE_URL);
        if (false === $redirectUrl) {
            throw new \InvalidArgumentException('The argument is not an URL');
        }
        $this->redirectUrl = $redirectUrl;
    }

    public function getCurrentUrl(): string
    {
        $host = $_SERVER['HttpHOST'] ?? 'localhost';
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        return $this->getCurrentScheme() . '://' . $host . $path;
    }

    public function getCurrentScheme(): string
    {
        return isset($_SERVER['HTTPS']) ? 'https' : 'http' ;
    }

    public function getLoginUrl(): string
    {
        $params = [
            'response_type' => self::OAUTH2_RESPONSE_TYPE,
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->getRedirectUrl(),
            'state' => $this->getState(),
            'scope' => implode('%20', self::SCOPE),
        ];
        return $this->buildUrl('authorization', $params);
    }

    public function buildUrl(string $endpoint, array $params): string
    {
        $url = self::OAUTH2_API_ROOT;
        return $url.$endpoint.'?'.$this->build_query($params);
    }

    public function build_query($params): string
    {
        $query='';
        foreach($params as $k => $v){
            $query.= $k.'='.$v.'&';
        }
        return rtrim($query,'&');
    }

    public static function responseToArray($response)
    {
        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }
}

<?php
declare(strict_types=1);


namespace Artisen2021\LinkedInSDK\Authentication;

use Artisen2021\LinkedInSDK\Exception\CouldNotGetAccessToken;
use Exception;

class AccessToken
{
    public string $token;
    public int $expiresIn;

    public function getToken()
    {
        return $this->token;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function setExpiresIn($expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    public static function fromResponse($response)
    {
        return static::fromResponseArray(
            Client::responseToArray($response)
        );
    }

    public static function fromResponseArray($responseArray)
    {
        if (!is_array($responseArray)) {
            throw new \InvalidArgumentException(
                'Argument is not array'
            );
        }
        if (!isset($responseArray['access_token'])) {
            throw new \InvalidArgumentException(
                'Access token is not available'
            );
        }
        if (!isset($responseArray['expires_in'])) {
            throw new \InvalidArgumentException(
                'Access token expiration date is not specified'
            );
        }
        return new static(
            $responseArray['access_token'],
            $responseArray['expires_in']
        );
    }
}

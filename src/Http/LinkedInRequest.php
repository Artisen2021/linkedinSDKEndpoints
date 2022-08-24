<?php
declare(strict_types=1);

namespace Artisen2021\LinkedInSDK\Http;

use Artisen2021\LinkedInSDK\Authentication\AccessToken;
use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Artisen2021\LinkedInSDK\Authentication\Client;
use Psr\Http\Message\ResponseInterface;


class LinkedInRequest
{
    /**
     * @throws Exception
     */
    public function send($method, $endpoint, $headers, $parameters): ResponseInterface
    {
        $requestBody = empty($parameters)
            ? json_encode($parameters, JSON_FORCE_OBJECT)
            : json_encode($parameters);

        $request = new Request($method, $endpoint, $headers, $requestBody);
        try {
            $response = (new GuzzleClient())->send($request);
        } catch (GuzzleException $e) {
            //we'll need to throw dedicated exception here
            throw new Exception($e->getMessage(), $e->getCode());
        }

        $this->responseHttpStatusCode = $response->getStatusCode();
        $this->responseHeaders = $response->getHeaders();

        return $response;
    }
}

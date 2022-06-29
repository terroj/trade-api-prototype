<?php

namespace Terroj\PayeerClient;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

/**
 * Http client for sending requests to the Payeer trade API.
 */
class TradeClient
{
    /**
     * Gets a payeer client id.
     *
     * @var string
     */
    private string $id;

    /**
     * Gets a payeer client key.
     *
     * @var string
     */
    private string $key;

    /**
     * Gets the default payeer URL address.
     */
    public const DEFAULT_PAYEER_URL = 'https://payeer.com/api/trade/';

    /**
     * Creates a new instance of TradePayeerClient.
     *
     * @param string $id An API Client id.
     * @param string $key An API Client key.
     * @param string|null $url The Payeer URL address, if don't provided, uses the default URL address.
     */
    public function __construct(string $id, string $key, string $url = null)
    {
        $this->id = $id;
        $this->key = $key;
        $this->url = $url ?? static::DEFAULT_PAYEER_URL;
    }

    /**
     * Sends a request to the specified Payeer trade API method.
     *
     * @param TradeMethods $method Any trade method.
     * @param array $body Request body.
     * @return PayeerResponse
     * @throws RequestException If a request error occurred.
     */
    public function Request(TradeMethods $method, array $body = [])
    {
        $client = $this->MakeDefaultHttpClient();
        $timestamp = $this->GetCurrentTimeStamp();

        $id = $this->id;
        $key = $this->key;

        $body = $this->SerializeBody(
            array_merge($body, ['ts' => $timestamp])
        );

        $sign = $this->SignBody($method, $key, $body);


        $headers = [
            'API-ID' => $id,
            'API-SIGN' => $sign,
            'Content-Type' => 'application/json'
        ];

        $request = new Request('POST', $method->value, $headers, $body);

        $response = new PayeerResponse(
            $client->send($request)
        );

        if ($response->IsError()) {
            throw RequestException::create($request, $response);
        }

        return $response;
    }

    /**
     * Creates the default guzzle http client with base uri.
     *
     * @return \GuzzleHttp\Client
     */
    public function MakeDefaultHttpClient(): \GuzzleHttp\Client
    {
        return new \GuzzleHttp\Client([
            'base_uri' => $this->url,
        ]);
    }

    /**
     * Creates a sign of the request body.
     *
     * @param TradeMethods $method Api method.
     * @param string $key Api private key.
     * @param string $body Serialized request body.
     * @param string $algo Sign algorithm.
     * @return string String that contains the signed request body.
     */
    protected function SignBody(
        TradeMethods $method,
        string $key,
        string $body,
        string $algo = 'sha256'
    ): string {
        return hash_hmac($algo, $method->value . $body, $key);
    }

    /**
     * Serializes a request body to a string.
     *
     * @param array $body Request body.
     * @return string
     */
    protected function SerializeBody(array $body): string
    {
        return json_encode($body);
    }

    /**
     * Returns the current timestamp.
     *
     * @return float
     */
    protected function GetCurrentTimeStamp(): float
    {
        return round(microtime(true) * 1000);
    }
}

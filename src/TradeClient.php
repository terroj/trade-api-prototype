<?php

namespace Terroj\PayeerClient;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Terroj\PayeerClient\Contacts\TradeClientInterface;

/**
 * Http client for sending requests to the Payeer trade API.
 */
class TradeClient implements TradeClientInterface
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
     * @param TradeMethods|string $method Any trade method.
     * @param array $body Request body.
     * @return PayeerResponse
     * @throws RequestException If a request error occurred.
     */
    public function request(TradeMethods|string $method, array $body = [])
    {
        if ($method instanceof TradeMethods) {
            $method = $method->value;
        }

        $client = $this->makeDefaultHttpClient();
        $timestamp = $this->getCurrentTimeStamp();

        $id = $this->id;
        $key = $this->key;

        $body = $this->serializeBody(
            array_merge($body, ['ts' => $timestamp])
        );

        $sign = $this->signBody($method, $key, $body);


        $headers = [
            'API-ID' => $id,
            'API-SIGN' => $sign,
            'Content-Type' => 'application/json'
        ];

        $request = new Request('POST', $method, $headers, $body);

        $response = new PayeerResponse(
            $client->send($request)
        );

        if ($response->isError()) {
            throw RequestException::create($request, $response);
        }

        return $response;
    }

    /**
     * Creates the default guzzle http client with base uri.
     *
     * @return \GuzzleHttp\Client
     */
    public function makeDefaultHttpClient(): \GuzzleHttp\Client
    {
        return new \GuzzleHttp\Client([
            'base_uri' => $this->url,
        ]);
    }

    /**
     * Creates a sign of the request body.
     *
     * @param string $method Api method.
     * @param string $key Api private key.
     * @param string $body Serialized request body.
     * @param string $algo Sign algorithm.
     * @return string String that contains the signed request body.
     */
    protected function signBody(
        string $method,
        string $key,
        string $body,
        string $algo = 'sha256'
    ): string {
        return hash_hmac($algo, $method . $body, $key);
    }

    /**
     * Serializes a request body to a string.
     *
     * @param array $body Request body.
     * @return string
     */
    protected function serializeBody(array $body): string
    {
        return json_encode($body);
    }

    /**
     * Returns the current timestamp.
     *
     * @return float
     */
    protected function getCurrentTimeStamp(): float
    {
        return round(microtime(true) * 1000);
    }
}

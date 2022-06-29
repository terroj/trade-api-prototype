<?php

use Terroj\PayeerClient\Trade;
use GuzzleHttp\Exception\RequestException;
use Terroj\PayeerClient\PayeerResponse;

/**
 * @deprecated 1.0.0 This usage is deprecated, use the Payer class
 * @example php Old usage
 * ```
 * $payeer = null;
 * try {
 *     $payeer = new Api_Trade_Payeer([
 *         'id' => $id,
 *         'key' => $key,
 *     ]);
 * 
 *     $result = $payeer->Info();
 * } catch (\Throwable $exception) {
 *     $error = $payeer->GetError();
 * }
 * ```
 * @example php Recommended usage
 * ```
 * try {
 *     $trade = new Trade($id, $key);
 *     $info = $trade->Info();
 * } catch (RequestException $exception) {
 *     $error = $exception->getResponse()->GetError();
 * } catch (\Throwable $exception) {
 *     // ...
 * }
 * ```
 */
class Api_Trade_Payeer
{
    /**
     * Gets or sets a trade instance.
     *
     * @var Trade
     */
    private Trade $trade;

    /**
     * Gets or sets the last http client error.
     *
     * @var array
     */
    private array $lastError = [];

    /**
     * Creates a new instance of Api_Trade_Payeer.
     *
     * @param string $params
     */
    public function __construct($params = [])
    {
        $this->trade = new Trade($params['id'], $params['key'], $params['url'] ?? null);
    }

    /**
     * Gets the last error data.
     *
     * @return array
     */
    public function GetError(): array
    {
        return $this->lastError;
    }

    /**
     * Returns limits, available pairs and their parameters.
     *
     * @return array
     */
    public function Info(): array
    {
        return $this->handle(function () {
            return $this->trade->Info();
        });
    }

    /**
     * Returns available orders for the specified pairs.
     *
     * @param string $pair
     * @return array List of pairs
     */
    public function Orders($pair = 'BTC_USDT'): array
    {
        return $this->handle(function () use ($pair) {
            return $this->trade->Orders($pair);
        });
    }

    /**
     * Returns the user's balance.
     *
     * @return array List of user's currencies.
     */
    public function Account(): array
    {
        return $this->handle(function () {
            return $this->trade->Account();
        });
    }

    /**
     * Creates an order of supported types: limit, market, stop limit.
     *
     * @param array $body
     * @return array parameters of the created order.
     */
    public function OrderCreate($body = []): array
    {
        return $this->handle(function () use ($body) {
            return $this->trade->OrderCreate($body);
        });
    }

    /**
     * Returns detailed information about your order by id.
     *
     * @param array $body
     * @return array order parameters.
     */
    public function OrderStatus($body = []): array
    {
        return $this->handle(function () use ($body) {
            return $this->trade->OrderStatus($body);
        });
    }

    /**
     * Returns detailed information about your order by id.
     *
     * @param array $body
     * @return array List of orders.
     */
    public function MyOrders($body = []): array
    {
        return $this->handle(function () use ($body) {
            return $this->trade->MyOrders($body);
        });
    }

    private function handle(callable $callable): array
    {
        try {
            return call_user_func($callable);
        } catch (RequestException $exception) {
            /** @var PayeerResponse $response */
            $response = $exception->getResponse();

            $this->lastError = $response->GetError();

            throw new Exception($response->GetErrorCode());
        }
    }
}

<?php

namespace Terroj\PayeerClient;

/**
 * Payeer trade client. 
 */
class Trade
{
    /**
     * Gets or sets the trade client.
     *
     * @var TradeClient
     */
    private TradeClient $client;

    /**
     * Creates a new instance of Trade.
     *
     * @param string $id An API Client id.
     * @param string $key An API Client key.
     * @param string|null $url The Payeer URL address, if don't provided, uses the default URL address.
     */
    public function __construct(string $id, string $key, string $url = null)
    {
        $this->client = new TradeClient($id, $key, $url);
    }

    /**
     * Returns limits, available pairs and their parameters.
     *
     * @return array
     * @example php
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
    public function Info(): array
    {
        return $this->client->Request(TradeMethods::INFO)->GetArrayResponse();
    }

    /**
     * Returns available orders for the specified pairs.
     *
     * @param string $pair
     * @return array List of pairs
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $pairs = $trade->Orders('BTC_USDT');
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->GetError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function Orders(string $pair = 'BTC_USDT'): array
    {
        $response = $this->client->Request(TradeMethods::ORDERS, [
            'pair' => $pair
        ])->GetArrayResponse();

        return $response['pairs'];
    }

    /**
     * Returns the user's balance.
     *
     * @return array List of user's currencies.
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $balances = $trade->Account();
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->GetError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function Account(): array
    {
        $response = $this->client->Request(TradeMethods::ACCOUNT)->GetArrayResponse();

        return $response['balances'];
    }

    /**
     * Creates an order of supported types: limit, market, stop limit.
     *
     * @param array $body
     * @return array parameters of the created order.
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $orders = $trade->OrderCreate([
     *         'pair' => 'TRX_USD',
     *         'type' => 'limit',
     *         'action' => 'buy, sell',
     *         'amount' => '10',
     *         'price' => '0.08',
     *     ]);
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->GetError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function OrderCreate(array $body = []): array
    {
        return $this->client->Request(TradeMethods::ORDER_CREATE, $body)->GetArrayResponse();
    }

    /**
     * Returns detailed information about your order by id.
     *
     * @param array $body
     * @return array order parameters.
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $status = $trade->OrderStatus([
     *         'order_id' => 37054293,
     *     ]);
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->GetError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function OrderStatus(array $body = []): PayeerResponse
    {
        $response = $this->client->Request(TradeMethods::ORDER_STATUS, $body);

        return $response['order'];
    }

    /**
     * Returns detailed information about your order by id.
     *
     * @param array $body
     * @return array List of orders.
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $status = $trade->MyOrders([
     *         'pair' => 'BTC_USD,TRX_USD',
     *         'action' => 'buy, sell'
     *     ]);
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->GetError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function MyOrders(array $body = []): PayeerResponse
    {
        $response = $this->client->Request(TradeMethods::MY_ORDERS, $body);

        return $response['items'];
    }
}

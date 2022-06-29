<?php

namespace Terroj\PayeerClient\Contacts;

use Terroj\PayeerClient\PayeerResponse;

/**
 * Payeer trade client interface. 
 */
interface TradeInterface
{
    /**
     * Returns limits, available pairs and their parameters.
     *
     * @return array
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $info = $trade->info();
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->getError();
     * } catch (\Throwable $exception) {
     *     // ...
     * }
     * ```
     */
    public function info(): array;

    /**
     * Returns available orders for the specified pairs.
     *
     * @param string $pair
     * @return array List of pairs
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $pairs = $trade->orders('BTC_USDT');
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->getError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function orders(string $pair = 'BTC_USDT'): array;

    /**
     * Returns the user's balance.
     *
     * @return array List of user's currencies.
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $balances = $trade->account();
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->getError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function account(): array;

    /**
     * Creates an order of supported types: limit, market, stop limit.
     *
     * @param array $body
     * @return array parameters of the created order.
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $orders = $trade->orderCreate([
     *         'pair' => 'TRX_USD',
     *         'type' => 'limit',
     *         'action' => 'buy, sell',
     *         'amount' => '10',
     *         'price' => '0.08',
     *     ]);
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->getError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function orderCreate(array $body = []): array;

    /**
     * Returns detailed information about your order by id.
     *
     * @param array $body
     * @return array order parameters.
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $status = $trade->orderStatus([
     *         'order_id' => 37054293,
     *     ]);
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->getError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function orderStatus(array $body = []): PayeerResponse;

    /**
     * Returns detailed information about your order by id.
     *
     * @param array $body
     * @return array List of orders.
     * @example php
     * ```
     * try {
     *     $trade = new Trade($id, $key);
     *     $status = $trade->myOrders([
     *         'pair' => 'BTC_USD,TRX_USD',
     *         'action' => 'buy, sell'
     *     ]);
     * } catch (RequestException $exception) {
     *     $error = $exception->getResponse()->getError();
     * } catch (\Throwable $exception) {
     *     //
     * }
     * ```
     */
    public function myOrders(array $body = []): PayeerResponse;
}

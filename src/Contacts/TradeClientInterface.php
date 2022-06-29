<?php

namespace Terroj\PayeerClient\Contacts;

use Terroj\PayeerClient\PayeerResponse;
use Terroj\PayeerClient\TradeMethods;

/**
 * Payeer trade client interface. 
 */
interface TradeClientInterface
{
    /**
     * Sends a request to the specified Payeer trade API method.
     *
     * @param TradeMethods|string $method Any trade method.
     * @param array $body Request body.
     * @return PayeerResponse
     * @throws RequestException If a request error occurred.
     */
    public function request(TradeMethods|string $method, array $body = []);
}

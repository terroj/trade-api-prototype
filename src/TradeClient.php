<?php

namespace Terroj\PayeerClient;

use Exception;

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

    private function Request($req = [])
    {
        $msec = round(microtime(true) * 1000);
        $req['post']['ts'] = $msec;

        $post = json_encode($req['post']);

        $sign = hash_hmac('sha256', $req['method'] . $post, $this->key);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://payeer.com/api/trade/" . $req['method']);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "API-ID: " . $this->id,
            "API-SIGN: " . $sign
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);

        if ($response['success'] !== true) {
            $this->error = $response['error'];

            throw new Exception($response['error']['code']);
        }

        return $response;
    }


    public function GetError()
    {
        return $this->error;
    }

    public function Info()
    {
        $response = $this->Request(array(
            'method' => 'info',
        ));

        return $response;
    }

    public function Orders($pair = 'BTC_USDT')
    {
        $response = $this->Request(array(
            'method' => 'orders',
            'post' => array(
                'pair' => $pair,
            ),
        ));

        return $response['pairs'];
    }


    public function Account()
    {
        $response = $this->Request(array(
            'method' => 'account',
        ));

        return $response['balances'];
    }


    public function OrderCreate($req = array())
    {
        $response = $this->Request(array(
            'method' => 'order_create',
            'post' => $req,
        ));

        return $response;
    }


    public function OrderStatus($req = array())
    {
        $response = $this->Request(array(
            'method' => 'order_status',
            'post' => $req,
        ));

        return $response['order'];
    }


    public function MyOrders($req = array())
    {
        $response = $this->Request(array(
            'method' => 'my_orders',
            'post' => $req,
        ));

        return $response['items'];
    }
}

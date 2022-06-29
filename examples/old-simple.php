<?php

require_once __DIR__ . '/../vendor/autoload.php';

$id = "6d402g64-dsv2-1361-91gg-7c76fbd11dc2";
$key = "y9ibLasdsdadPY9v";
$payeer = null;

try {
    $payeer = new Api_Trade_Payeer([
        'id' => $id,
        'key' => $key,
    ]);

    $result = $payeer->Info();
} catch (\Throwable $exception) {
    $error = $payeer->GetError();
}

dd($result);

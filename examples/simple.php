<?php

use Terroj\PayeerClient\Trade;

require_once __DIR__ . '/../vendor/autoload.php';

$id = "6d402g64-dsv2-1361-91gg-7c76fbd11dc2";
$key = "y9ibLasdsdadPY9v";

// Creates an instance of Payeer.
$payeer = new Trade($id, $key);
// Request info API method.
$result = $payeer->Info();

dd($result);

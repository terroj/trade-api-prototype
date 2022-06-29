### Introduction

This package is designed for easy use of the trade API of Payeer.

### Installation

Require this package with Composer:

```bash
composer require terroj/trade-api-prototype:1.0.0
```

### Usage

This simple example shows how to use this package.

```php
try {
    $id = "6d402g64-dsv2-1361-91gg-7c76fbd11dc2";
    $key = "y9ibLasdsdadPY9v";

    // Creates an instance of Payeer.
    $payeer = new Payeer($id, $key);
    // Request info API method.
    $result = $payeer->info();
} catch (RequestException $exception) {
    // Gets the Payeer response.
    /** @var PayeerResponse $response */
    $response = $exception->getResponse();

    // Retrieves the Payeer error.
    $error = $response->getError();
} catch (\Throwable $exception) {
    // Handles another exception.
    // ...
}
```

### Advanced Usage

If you want to make a request to another API method or get an original response from Payeer, you can use the example below:

```php
try {
    $id = "6d402g64-dsv2-1361-91gg-7c76fbd11dc2";
    $key = "y9ibLasdsdadPY9v";

    // Creates an instance of Payeer.
    $client = new TradeClient($id, $key);
    // Request info API method.
    $response = $client->request('another-method', ['param1' => 100]);

    // ...
} catch (RequestException $exception) {
    // Gets the Payeer response.
    /** @var PayeerResponse $response */
    $response = $exception->getResponse();

    // Retrieves the Payeer error.
    $error = $response->getError();
} catch (\Throwable $exception) {
    // Handles another exception.
    // ...
}
```

### Usage With Proxy

If you use proxy or something else and the default URI address doesn't work, you can specify a different URI address.

```php
$id = "6d402g64-dsv2-1361-91gg-7c76fbd11dc2";
$key = "y9ibLasdsdadPY9v";
$uri = "https://proxy-payeer.ru/api/trade/";

// Creates an instance of Payeer with a different URI address.
$payeer = new Payeer($id, $key, $uri);
```

> Pay attention that the URL contains the full URL for the trading API and doesn't contains the method.

### Migration guide

If you want to migrate from "payeer/trade-api-prototype" to "terroj/trade-api-prototype" but you want do it smoothly, follow this:

#### Step 1

Install [Composer](https://getcomposer.org/doc/00-intro.md) to your project.

#### Step 2

Require this package with Composer:

```bash
composer require terroj/trade-api-prototype:0.0.1
```

#### Step 3

That's it, now you can have a cup of coffee.

#### Step 4

Well, if you ready to migrate to a new usage format, just replace following code:

```php
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
```

with this:

```php
try {
    $trade = new Trade($id, $key);
    $result = $trade->info();
} catch (RequestException $exception) {
    $error = $exception->getResponse()->getError();
} catch (\Throwable $exception) {
    // ...
}
```

### License

This package is made available under the MIT License (MIT), Please see [License File](LICENSE).

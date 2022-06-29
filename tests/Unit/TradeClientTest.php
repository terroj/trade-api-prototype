<?php

namespace Terroj\PayeerClient\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Terroj\PayeerClient\TradeClient;
use Terroj\PayeerClient\TradeMethods;

/**
 * @group unit
 * @group trade
 */
class TradeClientTest extends TestCase
{
    /** @test */
    public function Request_ShouldReturnedAccountBalances(): void
    {
        // Arrange
        $id = "6d402g64-dsv2-1361-91gg-7c76fbd11dc2";
        $key = "y9ibLasdsdadPY9v";

        $USDTotal = 0.92;

        $body = json_encode([
            "success" => true,
            "balances" => [
                "USD" => [
                    "total" => $USDTotal,
                    "available" => $USDTotal,
                    "hold" => 0
                ],
            ]
        ]);
        $response = new Response(body: $body);

        /** @var MockInterface|TradeClient $client */
        $client = Mockery::mock(TradeClient::class, [$id, $key]);
        $client->makePartial();
        $client->shouldReceive('MakeDefaultHttpClient->send')->withArgs(
            function (RequestInterface $request) use ($id) {
                $uri = (string)$request->getUri();
                $apiIdHeader = $request->getHeader('API-ID')[0];
                $apiSignHeader = $request->getHeader('API-SIGN')[0];

                return $uri === 'account' &&
                    $apiIdHeader === $id &&
                    is_string($apiSignHeader);
            }
        )->andReturn($response);

        // Assert
        $response = $client->request(TradeMethods::ACCOUNT);

        // Act
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($body, $response->getBody()->getContents());

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isError());
        $this->assertTrue($response->getArrayResponse()['success']);
        $this->assertEquals($USDTotal, $response->getArrayResponse()['balances']['USD']['total']);
    }
}

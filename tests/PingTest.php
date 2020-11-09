<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Kerb\Partner\Partner;
use Kerb\Partner\Exceptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class PingTest extends TestCase
{

    public function setUp(): void
    {
        Partner::setApiKey('testing');
    }

    /**
     * @covers  Kerb\Partner\Partner
     * @covers  Kerb\Partner\Request
     * @covers  Kerb\Partner\Requests\Ping
     */
    public function testPingSuccess()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'PONG'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        Partner::$clientParams = ['handler' => $handlerStack];

        $response = Partner::send('ping');
        $this->assertEquals($response->getStatusCode(), 200);
    }
}

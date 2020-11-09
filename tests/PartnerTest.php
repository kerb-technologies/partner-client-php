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

class PartnerTest extends TestCase
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
    public function testRequestWithName()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'PONG'),
        ]);
        $request = Partner::makeRequest('ping', []);
        $request->setHeader('x-test', 'TESTING');

        $handlerStack = HandlerStack::create($mock);
        Partner::$clientParams = ['handler' => $handlerStack];

        $response = Partner::request($request);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     * @covers  Kerb\Partner\Partner
     * @covers  Kerb\Partner\Request
     * @covers  Kerb\Partner\Requests\Ping
     * @covers  Kerb\Partner\Exceptions\RotatedApiKey
     *
     */
    public function testExpiredKey()
    {
        $tomorrow = new \Datetime('+1 days');
        $mock = new MockHandler([
            new Response(200, [
                Partner::KERB_APIKEY_EXPIRE_AT_NAME => $tomorrow->getTimestamp(),
            ], 'PONG'),
        ]);


        $handlerStack = HandlerStack::create($mock);
        Partner::$clientParams = ['handler' => $handlerStack];

        Partner::$throwAtRotateKey = true;

        $request  = Partner::makeRequest('ping', []);

        $this->expectException(Exceptions\RotatedApiKey::class);
        $response = Partner::request($request);
    }
}

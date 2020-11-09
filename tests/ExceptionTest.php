<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Kerb\Partner\Partner;
use Kerb\Partner\Exceptions;

class ExceptionTest extends TestCase
{

    /**
     * @covers  Kerb\Partner\Partner
     * @covers  Kerb\Partner\Request
     * @covers  Kerb\Partner\Requests\Ping
     * @covers  Kerb\Partner\Exceptions\EmptyApiHost
     */
    public function testEmptyApiHost()
    {
        $apiHost = Partner::getApiHost();
        // remove KERB_PARTNER_HOST from env
        putenv('KERB_PARTNER_HOST=');
        try {
            $resp = Partner::send('ping');
        } catch (\Exception $exc) {
            $this->assertTrue($exc instanceof Exceptions\EmptyApiHost);
            /* $this->assertEquals($exc->getCode(), Exceptions::EMPTY_API_HOST); */
        }
        putenv('KERB_PARTNER_HOST=' . $apiHost);
    }

    /**
     * @covers  Kerb\Partner\Partner
     * @covers  Kerb\Partner\Request
     * @covers  Kerb\Partner\Requests\Ping
     * @covers  Kerb\Partner\Exceptions\EmptyApiKey
     */
    public function testEmptyApiKey()
    {
        $apiKey = Partner::getApiKey();
        Partner::setApiKey('');
        // remove KERB_PARTNER_HOST from env
        try {
            $resp = Partner::send('ping');
        } catch (\Exception $exc) {
            $this->assertTrue($exc instanceof Exceptions\EmptyApiKey);
        }
        Partner::setApiKey($apiKey);
    }

    /**
     * @covers  Kerb\Partner\Partner
     * @covers  Kerb\Partner\Request
     * @covers  Kerb\Partner\Exceptions\InvalidRequestName
     */
    public function testInvalidRequestName()
    {
        $this->expectException(Exceptions\InvalidRequestName::class);

        $resp = Partner::send('not-requests-name');
    }
}

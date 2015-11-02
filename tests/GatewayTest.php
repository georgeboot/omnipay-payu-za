<?php

namespace Omnipay\PayUZa;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '12.00'));

        $this->assertInstanceOf('\Omnipay\PayUZa\Message\PurchaseRequest', $request);
        $this->assertSame('12.00', $request->getAmount());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase(array('amount' => '12.00'));

        $this->assertInstanceOf('\Omnipay\PayUZa\Message\CompletePurchaseRequest', $request);
        $this->assertSame('12.00', $request->getAmount());
    }
}

<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetDataWithoutCard()
    {
        $this->request->initialize(array(
            'merchantId' => 'asdf',
            'sign' => '123',
            'testMode' => true,
            'amount' => '12.00',
            'currency' => 'CHF',
            'transactionId' => '123',
            'returnUrl' => 'https://www.example.com/success',
            'errorUrl' => 'https://www.example.com/error',
            'cancelUrl' => 'https://www.example.com/cancel'
        ));

        $expected = array(
            'merchantId' => 'asdf',
            'refno' => '123',
            'amount' => 1200,
            'currency' => 'CHF',
            'sign' => '123',
            'reqtype' => 'CAA',
            'successUrl' => 'https://www.example.com/success',
            'errorUrl' => 'https://www.example.com/error',
            'cancelUrl' => 'https://www.example.com/cancel'
        );

        $this->assertEquals($expected, $this->request->getData());
    }
}

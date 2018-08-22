<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class TokenizeRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new TokenizeRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetDataWithoutCard()
    {
        $this->request->initialize(array(
            'merchantId' => 'asdf',
            'sign' => '123',
            'testMode' => true,
            'currency' => 'CHF',
            'transactionId' => '123',
            'returnUrl' => 'https://www.example.com/success',
            'errorUrl' => 'https://www.example.com/error',
            'cancelUrl' => 'https://www.example.com/cancel'
        ));

        $expected = array(
            'merchantId' => 'asdf',
            'refno' => '123',
            'amount' => 0,
            'currency' => 'CHF',
            'sign' => '123',
            'successUrl' => 'https://www.example.com/success',
            'errorUrl' => 'https://www.example.com/error',
            'cancelUrl' => 'https://www.example.com/cancel',
            'useAlias' => 'yes',
        );

        $this->assertEquals($expected, $this->request->getData());
    }

    /**
     * No errorUrl set explicitly.
     */
    public function testErrorUrlDefaults()
    {
        $this->request->initialize(array(
            'merchantId' => 'asdfxxx',
            'sign' => '123',
            'testMode' => true,
            'currency' => 'CHF',
            'transactionId' => '123',
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel'
        ));

        $expected = array(
            'merchantId' => 'asdfxxx',
            'refno' => '123',
            'amount' => 0,
            'currency' => 'CHF',
            'sign' => '123',
            'successUrl' => 'https://www.example.com/return',
            'errorUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
            'useAlias' => 'yes',
        );

        $this->assertEquals($expected, $this->request->getData());
    }
}

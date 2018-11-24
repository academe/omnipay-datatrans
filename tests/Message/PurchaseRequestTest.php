<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;
use Omnipay\Datatrans\Gateway;

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

    /**
     * No errorUrl set explicitly.
     */
    public function testErrorUrlDefaults()
    {
        $this->request->initialize(array(
            'merchantId' => 'asdf',
            'sign' => '123',
            'testMode' => true,
            'amount' => '12.00',
            'currency' => 'CHF',
            'transactionId' => '123',
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel'
        ));

        $expected = array(
            'merchantId' => 'asdf',
            'refno' => '123',
            'amount' => 1200,
            'currency' => 'CHF',
            'sign' => '123',
            'reqtype' => 'CAA',
            'successUrl' => 'https://www.example.com/return',
            'errorUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel'
        );

        $this->assertEquals($expected, $this->request->getData());
    }

    /**
     * See https://github.com/academe/omnipay-datatrans/issues/6
     *
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testPayPalPurchaseCreateCardFails()
    {
        $this->request->initialize(array(
            'paymentMethod' => Gateway::PAYMENT_METHOD_PAP,
            'createCard' => true,
            //
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

        $this->request->send();
    }

    /**
     * See https://github.com/academe/omnipay-datatrans/issues/6
     */
    public function testPayPalPurchaseNonCreateCardSucceeds()
    {
        $this->request->initialize(array(
            'paymentMethod' => Gateway::PAYMENT_METHOD_PAP,
            'createCard' => false,
            //
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

        $this->request->send();
    }
}

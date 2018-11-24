<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;
use Omnipay\Datatrans\Gateway;

class AuthorizeRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * See https://github.com/academe/omnipay-datatrans/issues/6
     */
    public function testPayPalPurchaseCreateCardSucceeds()
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

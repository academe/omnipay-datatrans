<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class CreateCardTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new CreateCardRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testAmountAndAlias()
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

        $expected = [
            'amount' => 0,
            'useAlias' => 'yes',
        ];

        $this->assertArraySubset($expected, $this->request->getData());

        $this->assertSame('asdf0CHF123', $this->request->getHmacString());
    }
}

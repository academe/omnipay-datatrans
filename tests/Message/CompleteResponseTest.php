<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class CompleteResponseTest extends TestCase
{
    /**
     * @var CompletePurchaseResponse
     */
    private $responce;

    public function setUp()
    {
        parent::setUp();

        $this->response = new CompleteResponse(
            $this->getMockRequest(),
            [
                "merchantId" => "1100016000",
                "currency" => "CHF",
                "expm" => "12",
                "amount" => "1000",
                "returnCustomerCountry" => "USA",
                "acqAuthorizationCode" => "180013",
                "reqtype" => "CAA",
                "responseMessage" => "Authorized",
                "uppTransactionId" => "180317175618647060",
                "refno" => "e7e86bee-0ced-43b8-9ee9-e7fbb8d4ef31",
                "theme" => "DT2015",
                "testOnly" => "yes",
                "authorizationCode" => "913957205",
                "pmethod" => "VIS",
                "sign" => "148843730514034476",
                "responseCode" => "01",
                "expy" => "18",
                "status" => "success",
                "uppMsgType" => "web",
                "maskedCC" => "424242xxxxxx4242",
                "pmethod" => "VIS",
                "expy" => "18",
                "expm" => "12",
            ]
        );
    }

    public function testSuccess()
    {
        $this->assertSame('4242', $this->response->getNumberLastFour());
        $this->assertSame('XXXXXXXXXXXX4242', $this->response->getNumberMasked());
        $this->assertSame('424242xxxxxx4242', $this->response->getNumberMasked(null));
        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame(12, $this->response->getExpiryMonth());
        $this->assertSame(18, $this->response->getExpiryYear());
        $this->assertSame('12/18', $this->response->getExpiryDate('m/y'));
        $this->assertSame('e7e86bee-0ced-43b8-9ee9-e7fbb8d4ef31', $this->response->getTransactionId());
        $this->assertSame('180317175618647060', $this->response->getTransactionReference());
        $this->assertSame('VIS', $this->response->getUsedPaymentMethod());
    }
}

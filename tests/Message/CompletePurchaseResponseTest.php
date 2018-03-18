<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    /**
     * @var CompletePurchaseResponse
     */
    private $responce;

    public function setUp()
    {
        parent::setUp();

        $this->response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            [
                "merchantId" => "1100016107",
                "currency" => "CHF",
                "expm" => "12",
                "amount" => "1000",
                "returnCustomerCountry" => "USA",
                "acqAuthorizationCode" => "180013",
                "reqtype" => "CAA",
                "responseMessage" => "Authorized",
                "uppTransactionId" => "180317175618647060",
                "refno" => "17",
                "theme" => "DT2015",
                "testOnly" => "yes",
                "authorizationCode" => "913957205",
                "pmethod" => "VIS",
                "sign" => "180305143448434776",
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
    }
}

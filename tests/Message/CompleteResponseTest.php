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

        $this->responseSuccess = new CompleteResponse(
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

        $this->responseCancel = new CompleteResponse(
            $this->getMockRequest(),
            [
                "maskedCC" => "424242xxxxxx4242",
                "merchantId" => "1100016183",
                "aliasCC" => "70119122433810042",
                "uppTermsLink" => "https://example.co.uk/",
                "currency" => "GBP",
                "amount" => "2250",
                "uppReturnMaskedCC" => "yes",
                "uppWebResponseMethod" => "POST",
                "uppTransactionId" => "180820155020472779",
                "language" => "en",
                "theme" => "DT2015",
                "refno" => "134655588975",
                "testOnly" => "yes",
                "pmethod" => "VIS",
                "sign" => "66e480fd751555d707103fe0f66b7bfef8e55a416a57ff6f33f4d2daf58469d5",
                "status" => "cancel",
                "uppMsgType" => "web",
            ]
        );
    }

    public function testSuccess()
    {
        $this->assertSame('4242', $this->responseSuccess->getNumberLastFour());
        $this->assertSame('XXXXXXXXXXXX4242', $this->responseSuccess->getNumberMasked());
        $this->assertSame('424242xxxxxx4242', $this->responseSuccess->getNumberMasked(null));
        $this->assertTrue($this->responseSuccess->isSuccessful());
        $this->assertFalse($this->responseSuccess->isRedirect());
        $this->assertSame(12, $this->responseSuccess->getExpiryMonth());
        $this->assertSame(18, $this->responseSuccess->getExpiryYear());
        $this->assertSame('12/18', $this->responseSuccess->getExpiryDate('m/y'));
        $this->assertSame('e7e86bee-0ced-43b8-9ee9-e7fbb8d4ef31', $this->responseSuccess->getTransactionId());
        $this->assertSame('180317175618647060', $this->responseSuccess->getTransactionReference());
        $this->assertSame('VIS', $this->responseSuccess->getUsedPaymentMethod());
    }

    public function testCancelled()
    {
        $this->assertFalse($this->responseCancel->isSuccessful());
        $this->assertTrue($this->responseCancel->isCancelled());
    }
}

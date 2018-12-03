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

        $this->responsePendingPayPal = new CompleteResponse(
            $this->getMockRequest(),
            [
                "uppCustomerEmail"=> "jason.datatrans@academe.co.uk",
                "testOnly"=> "yes",
                "amount"=> "2250",
                "pmethod"=> "PAP",
                "itemamt"=> "2250",
                "uppWebResponseMethod"=> "POST",
                "sign2"=> "d878c372972f03c8947af261ea9522e99ac5bb48c1a079cb4a84ec16a987fa4d",
                "uppCustomerName"=> "Jason Judge",
                "sign"=> "7b59c12fa3dd680771b2cd8cdc4eb394cf12f2f4a39a8e8d7e5dde0e295670b7",
                "uppCustomerCountry"=> "DEU",
                "uppCustomerCity"=> "Freiburg",
                "taxamt1"=> "0",
                "uppCustomerZipCode"=> "79111",
                "taxamt0"=> "0",
                "payPalAllowNote"=> "1",
                "refno"=> "382583328377",
                "uppReturnMaskedCc"=> "yes",
                "uppCustomerDetails"=> "return",
                "language"=> "en",
                "uppDisplayShippingDetails"=> "yes",
                "reqtype"=> "CAA",
                "acqAuthorizationCode"=> "8Y894304WN001691G",
                "uppCustomerStreet"=> "ESpachstr. 1",
                "taxamt"=> "0",
                "name1"=> "Item2",
                "theme"=> "DT2015",
                "name0"=> "Item1",
                "number1"=> "2",
                "number0"=> "1",
                "pendingPayPal"=> "yes",
                "responseMessage"=> "PayPal transaction successful/pending",
                "uppTransactionId"=> "181203004654632546",
                "uppForwardCustomerDetails"=> "yes",
                "responseCode"=> "01",
                "merchantId"=> "1100016183",
                "redirectMethod"=> "GET",
                "currency"=> "GBP",
                "amt1"=> "250",
                "amt0"=> "1000",
                "version"=> "1.0.2",
                "authorizationCode"=> "719912597",
                "shippingamt"=> "0",
                "qty1"=> "1",
                "desc1"=> "This is Item Two",
                "uppTermsLink"=> "https://academe.co.uk/",
                "qty0"=> "2",
                "desc0"=> "This is Item One",
                "status" => "success",
                "uppMsgType"=> "web",
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

    public function testPendingPayal()
    {
        $this->assertTrue($this->responsePendingPayPal->isSuccessful());
        $this->assertFalse($this->responsePendingPayPal->isCancelled());
        $this->assertTrue($this->responsePendingPayPal->isPending());
    }
}

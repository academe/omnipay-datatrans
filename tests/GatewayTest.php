<?php

namespace Omnipay\Datatrans;

use Omnipay\Postfinance\Message\Helper;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId('asdf');
        $this->gateway->setSign('123');
        $this->gateway->setTestMode(true);

        $this->options = array(
            'amount' => '10.00',
            'currency' => 'CHF',
            'transactionId' => '1',
            'language' => 'fr',
            'cancelUrl' => 'https://www.example.com/cancel',
            'returnUrl' => 'https://www.example.com/success',
            'errorUrl' => 'https://www.example.com/error',
        );
    }

    //------------------------------------------------------------------------------------------------------------------
    // Purchase
    //------------------------------------------------------------------------------------------------------------------

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        // Expected redirect-data for the default options
        $data = array(
            'merchantId' => 'asdf',
            'sign' => '123',
            'refno' => '1',
            'amount' => 1000,
            'currency' => 'CHF',
            'language' => 'fr',
            'reqtype' => 'CAA',
            'uppReturnMaskedCC' => 'yes',
            'successUrl' => 'https://www.example.com/success',
            'errorUrl' => 'https://www.example.com/error',
            'cancelUrl' => 'https://www.example.com/cancel'
        );

        $this->assertInstanceOf(\Omnipay\Datatrans\Message\PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('POST', $response->getRedirectMethod());
        $this->assertEquals($data, $response->getRedirectData());
        $this->assertStringStartsWith('https://pay.sandbox.datatrans.com/upp/jsp/upStart.jsp', $response->getRedirectUrl());
    }

    //------------------------------------------------------------------------------------------------------------------
    // Purchase
    //------------------------------------------------------------------------------------------------------------------

    public function testAuthorize()
    {
        $response = $this->gateway->authorize($this->options)->send();

        // Expected redirect-data for the default options
        $data = array(
            'merchantId' => 'asdf',
            'sign' => '123',
            'refno' => '1',
            'amount' => 1000,
            'currency' => 'CHF',
            'reqtype' => 'NOA',
            'language' => 'fr',
            'uppReturnMaskedCC' => 'yes',
            'successUrl' => 'https://www.example.com/success',
            'errorUrl' => 'https://www.example.com/error',
            'cancelUrl' => 'https://www.example.com/cancel'
        );

        $this->assertInstanceOf(\Omnipay\Datatrans\Message\PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('POST', $response->getRedirectMethod());
        $this->assertEquals($data, $response->getRedirectData());
        $this->assertStringStartsWith('https://pay.sandbox.datatrans.com/upp/jsp/upStart.jsp', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $data = array(
            'testOnly' => true,
            'amount' => 10000,
            'pmethod' => 'VIS',
            'sign' => '123',
            'refno' => '1',
            'returnCustomerCountry' => 'USA',
            'reqtype' => 'CAA',
            'acqAuthorizationCode' => 1,
            'theme' => 'DT2015',
            'responseMessage' => 'Authorized',
            'uppTransactionId' => "180318151850542785",
            'expy' => 18,
            'expm' => 12,
            'responseCode' => '01',
            'merchantId' => 'asdf',
            'currency' => 'CHF',
            'authorizationCode' => 1,
            'status' => 'success',
            'uppMsgType' => 'web'
        );

        $this->getHttpRequest()->request->replace($data);
        $this->getHttpRequest()->setMethod('POST');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertInstanceOf(\Omnipay\Datatrans\Message\CompleteResponse::class, $response);
        $this->assertFalse($response->isRedirect());
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1', $response->getTransactionId());
        $this->assertEquals('180318151850542785', $response->getTransactionReference());
        $this->assertEquals('', $response->getMessage());
    }


    public function testCompletePurchaseError()
    {
        $data = array(
            'testOnly' => true,
            'amount' => 10000,
            'pmethod' => 'VIS',
            'sign' => '123',
            'refno' => '1',
            'returnCustomerCountry' => 'USA',
            'reqtype' => 'CAA',
            'acqErrorCode' => 50,
            'theme' => 'DT2015',
            'responseMessage' => 'Authorized',
            'expy' => 18,
            'expm' => 12,
            'responseCode' => '01',
            'merchantId' => 'asdf',
            'currency' => 'CHF',
            'status' => 'error',
            'uppMsgType' => 'web',
            'errorMessage' => 'declined',
            'errorDetail' => 'Declined'
        );

        // create sha hash for the given data

        $this->getHttpRequest()->query->replace($data);
        $this->getHttpRequest()->setMethod('GET');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
    }

    public function testCompletePurchaseCancel()
    {
        $data = array(
            'sign' => '123',
            'merchantId' => 'asdf',
            'uppTransactionId' => '1',
            'theme' => 'DT2015',
            'amount' => 10000,
            'testOnly' => true,
            'currency' => 'CHF',
            'refno' => '1',
            'status' => 'cancel',
            'uppMsgType' => 'web'
        );

        $this->getHttpRequest()->request->replace($data);
        $this->getHttpRequest()->setMethod('POST');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
    }

    public function testPurchaseSignature()
    {
        $this->gateway->setHmacKey1('ababababababababababababababababababab');

        $response = $this->gateway->purchase($this->options)->send();

        // Expected redirect-data for the default options
        $data = array(
            'merchantId' => 'asdf',
            'sign' => 'b9603c1be2b09536d1ad2314ffedda2a101276e54424e1689753ff127efb49e4',
            'refno' => '1',
            'amount' => 1000,
            'currency' => 'CHF',
            'language' => 'fr',
            'reqtype' => 'CAA',
            'uppReturnMaskedCC' => 'yes',
            'successUrl' => 'https://www.example.com/success',
            'errorUrl' => 'https://www.example.com/error',
            'cancelUrl' => 'https://www.example.com/cancel'
        );

        $this->assertInstanceOf(\Omnipay\Datatrans\Message\PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('POST', $response->getRedirectMethod());
        $this->assertEquals($data, $response->getRedirectData());
        $this->assertStringStartsWith('https://pay.sandbox.datatrans.com/upp/jsp/upStart.jsp', $response->getRedirectUrl());
    }

    public function testCompletePurchaseValidSignatureKey1()
    {
        $data = array(
            'sign' => '123',
            'merchantId' => 'asdf',
            'uppTransactionId' => '1',
            'theme' => 'DT2015',
            'amount' => 10000,
            'testOnly' => true,
            'currency' => 'CHF',
            'refno' => '1',
            'status' => 'cancel',
            'uppMsgType' => 'web',
            'sign2' => 'abece57c860169e21d4b290f3a95b9632425ea69bc55802e7c6abe10bb7b2ba9',
        );

        $this->getHttpRequest()->request->replace($data);
        $this->getHttpRequest()->setMethod('POST');

        $this->gateway->setHmacKey1('ababababababababababababababababababab');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
    }

    public function testCompletePurchaseValidSignatureKey2()
    {
        $data = array(
            'sign' => '123',
            'merchantId' => 'asdf',
            'uppTransactionId' => '1',
            'theme' => 'DT2015',
            'amount' => 10000,
            'testOnly' => true,
            'currency' => 'CHF',
            'refno' => '1',
            'status' => 'cancel',
            'uppMsgType' => 'web',
            'sign2' => 'abece57c860169e21d4b290f3a95b9632425ea69bc55802e7c6abe10bb7b2ba9',
        );

        $this->getHttpRequest()->request->replace($data);
        $this->getHttpRequest()->setMethod('POST');

        // Key2 will override key1.
        $this->gateway->setHmacKey1('cdcdcdcdcdcdcdcdcdcd');
        $this->gateway->setHmacKey2('ababababababababababababababababababab');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * @expectedException \Exception
     */
    public function testCompletePurchaseInvalidSignature()
    {
        $data = array(
            'sign' => '123',
            'merchantId' => 'asdf',
            'uppTransactionId' => '1',
            'theme' => 'DT2015',
            'amount' => 10000,
            'testOnly' => true,
            'currency' => 'CHF',
            'refno' => '1',
            'status' => 'success',
            'uppMsgType' => 'web',
            'sign2' => 'abababababababababab', // invalid
        );

        $this->getHttpRequest()->request->replace($data);
        $this->getHttpRequest()->setMethod('POST');

        $this->gateway->setHmacKey1('ababababababababababababababababababab');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
    }
}

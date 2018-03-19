# Omnipay: Datatrans

**Datatrans Gateway for the Omnipay PHP payment processing library.**

[![Build Status](https://api.travis-ci.org/w-vision/omnipay-datatrans.png)](https://travis-ci.org/w-vision/omnipay-datatrans)
[![Code Coverage](https://scrutinizer-ci.com/g/w-vision/omnipay-datatrans/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/w-vision/omnipay-datatrans/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/w-vision/omnipay-datatrans/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/w-vision/omnipay-datatrans/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/w-vision/omnipay-datatrans/v/stable)](https://packagist.org/packages/w-vision/omnipay-datatrans)
[![Latest Unstable Version](https://poser.pugx.org/w-vision/omnipay-datatrans/v/unstable)](https://packagist.org/packages/w-vision/omnipay-datatrans)
[![License](https://poser.pugx.org/w-vision/omnipay-datatrans/license)](https://packagist.org/packages/w-vision/omnipay-datatrans)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+.

This Gateway implements offsite payments via Datatrans. Purchase and Authorization are available, capturing an authorized payment has to be performed via Datatrans backend (not currently implemented for this Gateway).

## Installation

Omnipay can be installed using [Composer](https://getcomposer.org/). [Installation instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

Run the following command to install omnipay and the datatrans gateway:

    composer require w-vision/omnipay-datatrans:^1.0.0

## Basic (Minimal) Usage

Payment requests to the Datatrans Gateway must at least supply the following parameters:

* `merchantId` Your merchant ID
* `transactionId` unique merchant site transaction ID
* `amount` monetary amount (major units fro Omnipay 2.x)
* `currency` currency, ISO ??? code
* `sign` Your sign identifier. Can be found in datatrans backend.

Note: this minimal example does not actually sign or encrypt your request.
See below for details of settings for a more secure approach.

```php
$gateway = Omnipay::create('Datatrans');
$gateway->setMerchantId('{merchantId}');
$gateway->setSign('{sign}');

// Send purchase request. authorize() is also supported.

$response = $gateway->purchase([
    'transactionId' => '{merchant-site-id}',
    'amount' => '10.00',
    'currency' => 'CHF',
])->send();

// This is a redirect gateway, so redirect right away.
// By degault, this will be a POST redirect.

$response->redirect();
```

The results can be read on return:

```php
// TODO complete messages
```

### Optional Parameters

Additional parameters change the behaviour of the gateway.
They can be set in the `purchase()` parameter array, or via setters `setParamName()`.

* `language` - The language to be used by the UI. e.g. 'en', 'de', 'fr'.
* `returnMethod` - The HTTP method used in returning the user to your merchant site.
  Defaults to POST, which requires an SSL connection (which is recommended anyway).
  Can be set to GET if necessary. Will default to the Datatrans account setting..
* `returnUrl`/`cancelUrl`/`errorUrl` - All must be set either in the back-end Datatrans
  account or when the payment request is made.
* `paymentMethod` - The three-letter payment method, e.g. VIS, ECA.
  If left unset, multiple payment methods will be offered to the visitor to choose.
  The documentation implies a comma-separated list of payment methods can be provided,
  but this results in an error indicating the payment method is not valid.
* `hmacKey1` - HMAC key 'sign' for signing outbound messages.
  If signing is configured in the account, then the shared key must be provided here.
* `hmacKey2` - alternative HMAC key used to sign inbound messages.
  If not set, will default to the value of hmacKey1.

## Hidden Mode

This mode requires credit card details to be passed through your merchant application.
It is not supported by this release of the driver drue to the PCI requirements involved.

## TODO

### Shared Optional Parameters

* Merchant Specific Parameters
* customTheme
* mfaReference
* refno2
* Refno3
* virtualCardno
* uppStartTarget
* uppReturnTarget
* uppTermsLink An external link to the merchant’s terms and conditions. Will be
* uppRememberMe
* uppDiscountAmount
* mode
* Customer address details
* Basket details

### Full response details

* Methods to access most response parameters

### Functionality

* Notification handler (POST handler, support FORM and XML payloads)
  This should be almost a duplicate of completeRequest/completeResponse
* Payment method selection list
* Additional parameters and results for different payment types
* Secure 3D support where applicable
* Capture of customer address when using PayPal
* Capture of card reference when making a payment (aka "credit card alias")
  (DONE, but awaiting account update so I can test this)
* Use of card alias (aliasCC) when making a payment (Omnipay cardReference)
* Allow zero amounts for generating a card alias
* Assert signing of XML settlements


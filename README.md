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

## Basic Usage

Payment requests to the Datatrans Gateway must at least supply the following parameters:

 - `merchantId` Your merchant ID
 - `transactionId` unique transaction ID
 - `amount` monetary amount
 - `currency` currency
 - `sign` Your sign identifier. Can be found in datatrans backend.

```php
$gateway = Omnipay::create('Datatrans');
$gateway->setMerchantId('merchantId');
$gateway->setSign('sign');

// Send purchase request
$response = $gateway->purchase([
    'transactionId' => '17',
    'amount' => '10.00',
    'currency' => 'CHF',
])->send();

// This is a redirect gateway, so redirect right away
$response->redirect();

```

## TODO

### Shared Optional Parameters

* useAlias
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

* Methods to access all

### Functionality

* Notification handler (POST handler)
* Alternative security settings (the "sign" without secret signing seems useless)
* Use supplied HTTP client rather than $_REQUEST (test for both GET and POST)
* Payment method selection list
* Additional parameters and results for different payment types
* Secure 3D where supported


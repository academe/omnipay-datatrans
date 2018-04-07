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

Table of Contents
=================

   * [Omnipay: Datatrans](#omnipay-datatrans)
   * [Table of Contents](#table-of-contents)
      * [Installation](#installation)
      * [Basic (Minimal) Usage](#basic-minimal-usage)
      * [Optional Gateway and Authorize Parameters](#optional-gateway-and-authorize-parameters)
         * [Signing Requests](#signing-requests)
         * [Getting A Card Reference](#getting-a-card-reference)
         * [Optional Parameters](#optional-parameters)
      * [Complete Response](#complete-response)
      * [Notification](#notification)
      * [Void](#void)
      * [Capture](#capture)
      * [Get Transaction](#get-transaction)
      * [Hidden Mode](#hidden-mode)
      * [TODO](#todo)
         * [Shared Optional Parameters](#shared-optional-parameters)
         * [Functionality](#functionality)

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
* `currency` ISO 4217 three-letter code
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
// By default, this will be a POST redirect.

$response->redirect();
```

## Optional Gateway and Authorize Parameters

### Signing Requests

It is recommended the requests are signed with a pre-shared key.
The SHA256 key is set in the gateway account and is set in the gateway:

```php
$gateway->setHmacKey1('3e6c83...{long-key}...6e502a');
```

The same key will be used for checking the signing of response messages,
if set on the gateway when handling responses.
The response can optionally be signed by a different key, which is set using
`$gateway->setHmacKey2()`, and that is documented later.

### Getting A Card Reference

If enabled on the gateway account, a reusable card reference can be created.
To trigger this behaviour, set the `createCard` parameter to `true` while
making a purchase or authorization:

```php
$request = $gateway->purchase([
   'createCard' => true,
   ...
]);
```

The card reference will be available in the notification server request or the
*complete* response:

```php
// Will return null if this feature is not enabled in the gateway account.
$reusableCardReference = $response->getCardReference();
```

If you just want the card reference without making a purchase,
then set a zero amount.
Alternatively use `$gateway->createCard()` to create a card reference.

```php
$response = $gateway->createCard([
    'transactionId' => '{merchant-site-id}',
    'currency' => 'GBP',
])->send();
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

## Complete Response

The results can be read on the user returning to the merchant site:

```php
$request = $gateway->completeAuthorize();
$response = $request->send();

$success = $response->isSuccessful();
$transactionReference = $response->getTransactionReference();
```

Note: If the `returnUrl` ends with ".htm", then *no data* will not be returned
with the user to the merchant site. The site must then use the notification
handler to get the result.

## Notification

The notification handler provides all the same data and methods as `completeResponse`.

```php
$notify = $gateway->acceptNotification();

$success = $notify->isSuccessful();
$transactionReference = $notify->getTransactionReference();
```

To check the notification is correction signed, the `send()` method is used.

The notification handler supports all the data delivery modes: GET, POST,
XML in header or XML in body.
The signing check supports `key1` and the extended `key2` (a different key
used for responses as for the original request).

```php
$notify->send();
```

This will return $notify, but will throw an exception if the signing checks fail.

## Void

```php
$voidRequest = $gateway->void([
    'transactionReference' => $authorizedTransactionReference,
    'transactionId' => $uniqueTtransactionId,
    'currency' => 'GBP',
    'amount' => $originalTransactionAmount,
]);
$voidResponse = $voidRequest->send();
```

## Capture

```php
$captureRequest = $gateway->capture([
    'transactionReference' => $authorizedTransactionReference,
    'transactionId' => $uniqueTtransactionId,
    'currency' => 'GBP',
    'amount' => $originalTransactionAmount,
]);
$captureResponse = $voidRequest->send();
```

## Get Transaction

A previous transaction can be fetched given bith its `transactionId` and `transactionReference`.
This incoludes transactions that were cancelled by the user.

```php
$request = $gateway->getTransaction([
    'transactionReference' => $originalTransactionReference,
    'transactionId' => $originalTransactionId,
]);
$response = $request->send();
```

Please note that (for now) the `isSuccessful()` method on the response refers to
the success of getting the transaction, and not the success of the authorization.
This is likely to change.

The transactino status is given by the numeric value of `$response->getResponseCode()`
and the values can be found in the Datatrans documentation. Some work is needed to map
these ~20 codes to a simple success/failed/pending/cancelled status.

The response will, by default, contain the extended transaction details, which include
any `cardReference` that was requested, and masked card numbers and expiry dates.

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
* uppDiscountAmount
* mode
* Customer name and address details
* Basket details

### Functionality

* Additional parameters and results for different payment types
* Secure 3D support where applicable
* Capture of customer address when using PayPal
* Authorize and purchase on previous payments
* Authorize and purchase on card token (subscriptions)


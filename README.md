# Omnipay: Datatrans

**Datatrans Gateway for the Omnipay PHP payment processing library.**

[![Build Status](https://api.travis-ci.org/academe/omnipay-datatrans.png)](https://travis-ci.org/academe/omnipay-datatrans)
<!-- [![Code Coverage](https://scrutinizer-ci.com/g/academe/omnipay-datatrans/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/academe/omnipay-datatrans/?branch=master) -->
<!-- [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/academe/omnipay-datatrans/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/academe/omnipay-datatrans/?branch=master) -->
[![Latest Stable Version](https://poser.pugx.org/academe/omnipay-datatrans/v/stable)](https://packagist.org/packages/academe/omnipay-datatrans)
[![Latest Unstable Version](https://poser.pugx.org/academe/omnipay-datatrans/v/unstable)](https://packagist.org/packages/academe/omnipay-datatrans)
[![License](https://poser.pugx.org/academe/omnipay-datatrans/license)](https://packagist.org/packages/academe/omnipay-datatrans)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+.

**This is for Omnipay 2.x with a minimum PHP version of 5.6.
Shortly, the 2.x version will be moved to a legacy branch and
the master branch will be updated for Omnipay 3.x, which is
currently in alpha.**

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
      * [Offline Authorization](#offline-authorization)
      * [Redirect Mode](#redirect-mode)
      * [iframe Mode/Inline Mode](#iframe-modeinline-mode)
      * [Lightbox Mode](#lightbox-mode)
      * [Hidden Mode](#hidden-mode)
      * [ItemBag/Basket](#itembagbasket)
      * [TODO](#todo)
         * [Additional Parameters (for various payment methods)](#additional-parameters-for-various-payment-methods)
         * [Functionality](#functionality)

This Gateway implements offsite payments via Datatrans.
Purchase and Authorization are available, capturing an authorized payment has to be performed
via Datatrans XML backend.

## Installation

Omnipay can be installed using [Composer](https://getcomposer.org/).
[Installation instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

Run the following command to install omnipay and the datatrans gateway:

    composer require academe/omnipay-datatrans:^2.0

While this is in development - before it is released on [packagist](https://packagist.org/) -
you will need this entry in your `composer.json`:

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/academe/omnipay-datatrans"
        }
    ],
```

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
  Supporrted values can be found in constans `\Omnipay\Datatrans\Gateway::PAYMENT_METHOD_xxx`
* `hmacKey1` - HMAC key 'sign' for signing outbound messages.
  If signing is configured in the account, then the shared key must be provided here.
* `hmacKey2` - alternative HMAC key used to sign inbound messages.
  If not set, will default to the value of hmacKey1.
* A `cardReference` and optional expiry dates (in the `CreditCard` object, if the
  `cardReference` is for a credit card) can be supplied. This will pre-populate the
  card details for the user, so that they need only enter their CVV to authorize
  a payment.
* Many other optional parameters are supported, generally all those in the official
  documentaion except where listed in the TODO section here. These will be added as
  time permits.

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

This will return `$notify`, but will throw an exception if the signing checks fail.

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

*Up to* the original authotization amount can be captured.

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

A previous transaction can be fetched given either its `transactionId` or
`transactionReference`. The `transactionReference` is preferred.
This includes transactions that were cancelled by the user.

```php
$request = $gateway->getTransaction([
    'transactionReference' => $originalTransactionReference,
    // or
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

## Offline Authorization

Some payment methods support offline authorization using a `cardReference`.

First a `cardReference` must be obtained using `authorize`, `purchase` or
`createCard`. If this is done for a PayPal (PAP) payment method, then PayPal
will create an *order* which can have a series of amounts authorized up to
the maximum of the original order. The PayPal order ID is returned as the
`cardReference` and is used just like the `cardReference` in other opayment
methods.

A separate gateway is used to do offline payments, initialized using the
same parameters as the redirect gateway, but like this:

```php
$xmlGateway = Omnipay::create('Datatrans\Xml');
```

If the `cardReference` was for a credit card, then the expiry date must be
supplied with that reference. This is done using the Omnipsy `CreditCard`
class:

```php
// Minimum data for the card is the expiry month and year.
$card = new \Omnipay\Common\CreditCard([
    'expiryMonth' => $expiryMonth,
    'expiryYear' => $expiryYear,
    // The saved cardReference can be supplied here or later.
    'number' => $cardReference,
]);

//$response = $xmlGateway->purchase([
$response = $xmlGateway->authorize([
    'card' => $card,
    // Supply the card reference here if not in the $card object:
    'cardReference' => $cardReference,
    'amount' => '20.00',
    'currency' => 'EUR',
    'transactionId' => $transactionId,
    // The original payment method
    'paymentMethod' => \Omnipay\Datatrans\Gateway::PAYMENT_PAYMENT_METHOD_VIS,
])->send();
```

For non-credit card payment methods, the `CreditCard` object is not needed since
there will be no expiry date. Just the previously saved `cardReference` is passed
in. The `cardReference` is a generic term used for a number of card and non-card
payment methods.

## Redirect Mode

This is the standard mode for authorizing a payment, where the user will be taken
to a remove payment form and returned when they have finished.

The redirect can be either a `POST` or a `GET` redirect, defaulting to `POST`.
The `setRedirectmethod()` parameter takes either "GET" or "POST" to set the
redirection mode.

## iframe Mode/Inline Mode

By putting the redirect mode into GET `mode`, the URL for the iframe becomes the
redirect URL: `$response->getRedirectUrl()`

The iframe content is put into a simplified theme by setting `$request->setInline(true)`

```php
$response = $gateway->purchase(['inline' => true, 'redirectMethod' => 'GET', ...]);

echo '<iframe width="600" height="500" frameborder="0" border="0" src="'.$response->getRedirectUrl().'" />';
```

The iframe mode appears to require exactly one payment method, making the
payment method mandatory.

## Lightbox Mode

TBC

## Hidden Mode

This mode requires credit card details to be passed through your merchant application.
It is not supported by this release of the driver drue to the PCI requirements involved.

## ItemBag/Basket

The standard Omnipay Itembag is supported for the PayPal (PAP) mayment method.
Some notes, since the ItemBag can be inflexible and a little ambiguous:

* The `price` of each item is assumed to be the gross unit price.
* The `price` units are considered minor units if an integer (e.g. 123 or "123")
  or major units if a floating point number (e.g. 4.56 or "4.56").
* Shipping is set to zero.
* Tax is set to zero.
* The total `ItemBag` amount *must* equal the total order amount.

## TODO

### Additional Parameters (for various payment methods)

* Payolution mandatory parameters validation
* Aduno surprize specific parameters
* Migros Bank Payment mdpUserId and mdpAlias parameter + txnMbRefNo return param
* Swisscom Easypay parameters
* SwissBilling most customer details mandatory + optional shipping details + basket
* MasterPass Wallet basket + confirmationUrl parameter + shipping and billing address
  return details + lots more funky wallet pairing stuff with long-access tokens
  \+ multilevel XML files come into this
* uppCustomerLanguage is mandatory for a few gateways, e.g. Accarda Kauf-auf Rechnung
* Accarda Kauf-auf Rechnung mandatory customer details + tonnes of new specific parameters
  \+ lots more return parameters
* Byjuno customer details mandatory + optional shipping details
* LoyLogic Pointspay very simple basket (only supports purchase)
* Girosolution Giropay simply basket + bank details return params

### Functionality

* Capture of customer address when using PayPal
* Support lightbox mode (iframe)
* Support inline mode (JavaScript)
* AVS (address verification) by web interface and XML back-end
* Tests needed especially around the multiple notification methods and formats.


<?php

namespace Omnipay\Datatrans\Message;

/**
 * w-vision
 *
 * LICENSE
 *
 * This source file is subject to the MIT License
 * For the full copyright and license information, please view the LICENSE.md
 * file that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2016 Woche-Pass AG (http://www.w-vision.ch)
 * @license    MIT License
 */

/**
 * The abstract request for redirect requests.
 * These involve sending the end user directly to the remote gateway
 * as the very first step.
 */

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Datatrans\Traits\HasGatewayParameters;
use Omnipay\Datatrans\Gateway;

abstract class AbstractRedirectRequest extends AbstractRequest
{
    use HasGatewayParameters;

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign');

        $data = [
            'merchantId'        => $this->getMerchantId(),
            'amount'            => $this->getAmountInteger(),
            'currency'          => $this->getCurrency(),
            'refno'             => $this->getTransactionId(),
        ];

        // The kind of redirect the merchant site would like.

        if ($this->getRedirectMethod()) {
            $data['redirectMethod'] = $this->getRedirectMethod();
        }

        // If the amount is zero, then the merchant site is seeking
        // authorisation for the card (or other payment method) only.
        // Some docuemnts list using '1' instead of zero, but both seem
        // to work.

        if ($this->getAmountInteger() === 0) {
            $data['uppAliasOnly'] = Gateway::CARD_ALIAS_ONLY;
        }

        if ($card = $this->getCard()) {
            // The card alias could be set, with optional expiry date.

            if ($card->getExpiryMonth()) {
                $data['expm'] = $card->getExpiryMonth();
                $data['expy'] = $card->getExpiryDate('y');
                $data['aliasCC'] = $card->getNumber();
            }
        }

        // The card reference be provided without a card object and without
        // an expiry date.

        if ($this->getCardReference()) {
            $data['aliasCC'] = $this->getCardReference();
        }

        $data['sign'] = $this->getSigning();

        if ($this->getReturnMethod()) {
            $data['uppWebResponseMethod'] = $this->getReturnMethod();
        }

        if ($this->getLanguage()) {
            $data['language'] = $this->getLanguage();
        }

        if ($this->requestType) {
            $data['reqtype'] = $this->requestType;
        }

        if ((bool) $this->getMaskedCard()) {
            $data['uppReturnMaskedCC'] = Gateway::RETURN_MASKED_CC;
        }

        if ((bool) $this->getCreateCard()) {
            $data['useAlias'] = Gateway::USE_ALIAS;
        }

        if ((bool) $this->getCreateCardAskUser()) {
            $data['uppRememberMe'] = Gateway::USE_ALIAS_ASK_USER;
        }

        if ($this->getPaymentMethod()) {
            // 'method' must be lower-case to be recognised by the gateway.
            // Some documentation examples show this as lowerCamelCase, but
            // that is incorrect.

            $data['paymentmethod'] = $this->getPaymentMethod();
        }

        // Additional parameters for specific payment types.

        switch ($this->getPaymentMethod()) {
            case Gateway::PAYMENT_METHOD_PAP:
                // Paypal
                $data = $this->extraParamsPAP($data);
                break;
            case Gateway::PAYMENT_METHOD_PEF:
                // Swiss PostFinance E-Finance
                $data = $this->extraParamsPEF($data);
                break;
            case Gateway::PAYMENT_METHOD_PFC:
                // Swiss PostFinance Card
                $data = $this->extraParamsPFC($data);
                break;
        }

        // These URLs are optional here, if set in the account.

        if ($this->getReturnUrl() !== null) {
            $data['successUrl'] = $this->getReturnUrl();
        }

        if ($this->getCancelUrl() !== null) {
            $data['cancelUrl'] = $this->getCancelUrl();
        }

        if ($this->getErrorUrl() !== null) {
            $data['errorUrl'] = $this->getErrorUrl();
        }

        return $data;
    }

    /**
     * Additional parameters for PayPal (PAP).
     */
    protected function extraParamsPAP(array $data)
    {
        return $data;
    }

    /**
     * Additional parameters for Swiss PostFinance E-Finance (PEF)/
     */
    protected function extraParamsPEF(array $data)
    {
        return $data;
    }

    /**
     * Additional parameters for Swiss PostFinance Card (PFC)/
     */
    protected function extraParamsPFC(array $data)
    {
        return $data;
    }

    /**
     * @return ResponseInterface
     */
    public function send()
    {
        return $this->sendData($this->getData());
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new RedirectResponse($this, $data);
    }
}

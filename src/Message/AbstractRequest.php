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

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Datatrans\Traits\HasGatewayParameters;
use Omnipay\Datatrans\Gateway;

/**
 * Datatrans abstract request.
 * Implements all property setters and getters.
 */
abstract class AbstractRequest extends OmnipayAbstractRequest
{
    use HasGatewayParameters;

    /**
     * Get the MerchantId
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param string $value your datatrans merchant ID
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get the HMAC string for [the few] protected fields to be signed.
     *
     * @return string
     */
    public function getHmacString()
    {
        $data = [
            $this->getMerchantId(),
        ];

        // If the amount is zero, then use "ppAliasOnly" instead to show
        // we only want the card to be authorised.
        $data[] = $this->getAmountInteger() ?: 'uppAliasOnly';

        $data[] = $this->getCurrency();
        $data[] = $this->getTransactionId();

        if ((bool)$this->getPayPalOrderId()
            && $this->getPaymentMethod() === Gateway::PAYMENT_METHOD_PAP
        ) {
            $data[] = 'PayPalOrderId';
        }

        return implode('', $data);
    }

    /**
     * Calculate the "sign" string to send to the gateway.
     * What the gateway requires will depend on the security settings in the
     * gateway account.
     * What the merchant site sends will depend on which keys have been provided
     * in the gateway parameters.
     *
     * @return string
     */
    public function getSigning()
    {
        if ($hmacKey1 = $this->getHmacKey1()) {
            // A few important fields are signed.
            $sign = hash_hmac('SHA256', $this->getHmacString(), hex2bin($hmacKey1));
        } else {
            // Don't use this method. It is useless.
            $sign = $this->getSign();
        }

        return $sign;
    }
}

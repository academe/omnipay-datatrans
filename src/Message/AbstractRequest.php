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

    public function getHmacData()
    {
        $data = [
            $this->getMerchantId(),
        ];

        // If the amount is zero, then flag up "ppAliasOnly" to show we only
        // want the card to be authorised.
        $data[] = $this->getAmountInteger() ?: 'uppAliasOnly';

        $data[] = $this->getCurrency();
        $data[] = $this->getTransactionId();

        // TODO: "PayPalOrderId" if payPalOrderId=get.

        return implode('', $data);
    }
}

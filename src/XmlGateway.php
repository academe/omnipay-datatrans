<?php

namespace Omnipay\Datatrans;

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

use Omnipay\Datatrans\Message\TokenizeRequest;
use Omnipay\Datatrans\Message\XmlAuthorizationRequest;
use Omnipay\Datatrans\Message\XmlPurchaseRequest;
use Omnipay\Datatrans\Message\XmlSettlementRequest;
use Omnipay\Datatrans\Message\XmlSettlementCreditRequest;
use Omnipay\Datatrans\Message\XmlCancelRequest;
use Omnipay\Datatrans\Message\XmlStatusRequest;

/**
 * Datatrans Gateway
 */
class XmlGateway extends AbstractDatatransGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Datatrans XML';
    }

    /**
     * @param array $options
     * @return XmlPurchaseRequest
     */
    public function purchase(array $options = array())
    {
        return $this->createRequest(XmlPurchaseRequest::class, $options);
    }

    /**
     * @param array $options
     * @return XmlAuthorizationRequest
     */
    public function authorize(array $options = array())
    {
        return $this->createRequest(XmlAuthorizationRequest::class, $options);
    }

    /**
     * @param array $options
     * @return XmlSettlementRequest
     */
    public function settlementDebit(array $options = array())
    {
        return $this->createRequest(XmlSettlementRequest::class, $options);
    }

    /**
     * @param array $options
     * @return XmlSettlementCreditRequest
     */
    public function settlementCredit(array $options = array())
    {
        return $this->createRequest(XmlSettlementCreditRequest::class, $options);
    }

    /**
     * @param array $options
     * @return XmlCancelRequest
     */
    public function void(array $options = array())
    {
        return $this->createRequest(XmlCancelRequest::class, $options);
    }

    /**
     * @param array $options
     * @return XmlStatusRequest
     */
    public function status(array $options = array())
    {
        return $this->createRequest(XmlStatusRequest::class, $options);
    }
}

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

use Omnipay\Datatrans\Message\PurchaseRequest;
use Omnipay\Datatrans\Message\AuthorizeRequest;
use Omnipay\Datatrans\Message\CompleteRequest;
use Omnipay\Datatrans\Message\AcceptNotification;
use Omnipay\Datatrans\Message\XmlCancelRequest;
use Omnipay\Datatrans\Message\CreateCardRequest;
use Omnipay\Datatrans\Message\XmlSettlementRequest;
use Omnipay\Datatrans\Message\XmlStatusRequest;

/**
 * Datatrans Gateway
 */
class Gateway extends AbstractDatatransGateway
{
    public function getName()
    {
        return 'Datatrans';
    }

    /**
     * Start an authorize request.
     * TODO: can we look at any parameters to see if this needs to be diverted
     * to the XML authorization request? e.g. if a cardReference or transactionReference
     * were supplied?
     *
     * @param array $parameters array of options
     * @return PurchaseRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * Start a purchase request
     *
     * @param array $parameters array of options
     * @return PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * Complete an authorization.
     *
     * @param array $parameters
     * @return CompletePurchaseRequest
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest(CompleteRequest::class, $parameters);
    }

    /**
     * Complete a purchase.
     *
     * @param array $parameters
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompleteRequest::class, $parameters);
    }

    /**
     * Back channel notification.
     *
     * @param array $parameters
     * @return NotificationRequest
     */
    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest(AcceptNotification::class, $parameters);
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
     * @return XmlCancelRequest
     */
    public function capture(array $options = array())
    {
        return $this->createRequest(XmlSettlementRequest::class, $options);
    }

    /**
     * @param array $options
     * @return createCardRequest
     */
    public function createCard(array $options = array())
    {
        return $this->createRequest(CreateCardRequest::class, $options);
    }

    /**
     * @param array $options
     * @return XmlStatusRequest
     */
    public function getTransaction(array $options = array())
    {
        return $this->createRequest(XmlStatusRequest::class, $options);
    }
}

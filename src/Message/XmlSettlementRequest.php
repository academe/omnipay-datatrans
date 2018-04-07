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
 * Class XmlSettlementRequest
 *
 * @package Omnipay\Datatrans\Message
 */

use Omnipay\Datatrans\Gateway;

class XmlSettlementRequest extends AbstractXmlRequest
{
    /**
     * @var string
     */
    protected $requestType = Gateway::REQTYPE_COA;

    /**
     * @var string
     */
    protected $apiEndpoint = 'XML_processor.jsp';

    /**
     * @var string
     */
    protected $serviceName = 'paymentService';

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign', 'transactionReference');

        $requestType = $this->getRequestType();

        $data = array(
            'merchantId'        => $this->getMerchantId(),
            'amount'            => $this->getAmountInteger(),
            'currency'          => $this->getCurrency(),
            'refno'             => $this->getTransactionId(),
            'uppTransactionId'  => $this->getTransactionReference(),
            'reqtype'           => $requestType,
        );

        if ($this->getTransactionType()) {
            $data['transtype'] = $this->getTransactionType();
        }

        if ($this->getErrorEmail()) {
            $data['errorEmail'] = $this->getErrorEmail();
        }

        // CHECKME: requried for REF and REC, but is it allowed for other request types?
        if (
            $this->getAcqAuthorizationCode()
            && ($requestType === Gateway::REQTYPE_REF || $requestType === Gateway::REQTYPE_REC)
        ) {
            $data['acqAuthorizationCode'] = $this->getAcqAuthorizationCode();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return Gateway::TRANSACTION_TYPE_DEBIT;
    }
}

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
    protected $apiEndpoint = 'XML_processor.jsp';

    /**
     * @var string
     */
    protected $serviceName = 'paymentService';

    /**
     * @var int
     */
    protected $serviceVersion = 3;

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
            'uppTransactionId'  => $this->getTransactionReference(),
            'refno'             => $this->getTransactionId(),
            'reqtype'           => $requestType,
            'transtype'         => $this->getTransactionType()
        );

        if ($this->getErrorEmail()) {
            $data['errorEmail'] = $this->getErrorEmail();
        }

        if ($this->getAcqAuthorizationCode()) {
            $data['acqAuthorizationCode'] = $this->getAcqAuthorizationCode();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return Gateway::REQTYPE_COA;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return Gateway::TRANSACTION_TYPE_DEBIT;
    }
}

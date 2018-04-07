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
 * Class XmlStatusRequest
 *
 * @package Omnipay\Datatrans\Message
 */

use Omnipay\Datatrans\Gateway;

class XmlStatusRequest extends AbstractXmlRequest
{
    /**
     * @var string
     */
    protected $apiEndpoint = 'XML_status.jsp';

    /**
     * @var string
     */
    protected $serviceName = 'statusService';

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign', 'transactionReference');

        $data = array(
            'merchantId'        => $this->getMerchantId(),
            'sign'              => $this->getSign(),
            'uppTransactionId'  => $this->getTransactionReference(),
            'refno'             => $this->getTransactionId()
        );

        // Default to the extended response format, which provides cardReference
        // maskedCard, etc.

        $data['reqtype'] = $this->getRequestType() ?: Gateway::REQTYPE_STX;

        return $data;
    }
}

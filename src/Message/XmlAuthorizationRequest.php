<?php
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

namespace Omnipay\Datatrans\Message;

/**
 * Class XmlSettlementRequest
 *
 * @package Omnipay\Datatrans\Message
 */
class XmlAuthorizationRequest extends AbstractXmlRequest
{
    /**
     * @var array
     */
    protected $optionalParameters = array(
        'reqtype',
        'uppCustomerIpAddress'
    );

    /**
     * @var string
     */
    protected $apiEndpoint = 'XML_authorize.jsp';

    /**
     * @var string
     */
    protected $serviceName = 'authorizationService';

    /**
     * @var int
     */
    protected $serviceVersion = 3;

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign', 'card');

        $data = array(
            'amount'     => $this->getAmountInteger(),
            'currency'   => $this->getCurrency(),
            'aliasCC'    => $this->getCard()->getNumber(),
            'expm'       => $this->getCard()->getExpiryMonth(),
            'expy'       => $this->getCard()->getExpiryDate('y'),
            'useAlias' => 'no'
        );

        foreach ($this->optionalParameters as $param) {
            $value = $this->getParameter($param);

            if ($value) {
                $data[$param] = $value;
            }
        }

        return $data;
    }

    /**
     * @param $data
     * @return XmlAuthorizationResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new XmlAuthorizationResponse($this, $data);
    }
}

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
 * Request an offline authorization based on a previously captured
 * credit card reference or previous authorization.
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
     * This returns the data used for the "request" element of the XML request
     * data. This is further wrapped with the merchant ID and transaction ID
     * as the XML is constructed.
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign');

        $data = array(
            'amount'     => $this->getAmountInteger(),
            'currency'   => $this->getCurrency(),
        );

        // Some assumptions here.
        // If the card object is supplied, and it has an expiry date set,
        // then assume this is a card payment. Otherwise assume this is a
        // non-card payment. The required parameters differs slightly between tbe two.
        // See: https://www.datatrans.ch/alias-tokenization/using-the-alias

        if ($this->getCard() !== null && $this->getCard()->getExpiryMonth()) {
            // This is an offline credit card payment.

            $card = $this->getCard();

            // Spotted in some documentation, but not all:
            // https://www.datatrans.ch/showcase/authorisation/xml-authorisation
            //$data['reqtype'] = $this->getPaymentMethod();

            $data['aliasCC'] = $card->getNumber() ?: $this->getCardReference();
            $data['expm'] = $card->getExpiryMonth();
            $data['expy'] = $card->getExpiryDate('y');
        } else {
            // This is a non-credit card payment.

            $data['aliasCC'] = $this->getCardReference();
            $data['pmethod'] = $this->getPaymentMethod();
        }

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

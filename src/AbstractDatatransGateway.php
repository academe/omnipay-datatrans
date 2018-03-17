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

use Omnipay\Common\AbstractGateway;
use Omnipay\Datatrans\Message\TokenizeRequest;
use Omnipay\Datatrans\Traits\HasGatewayParameters;

/**
 * Datatrans Gateway (payment form).
 */
abstract class AbstractDatatransGateway extends AbstractGateway
{
    use HasGatewayParameters;

    /**
     * @var string reqtype parameter to indicate authorize only vs settle immediately
     */
    const REQTYPE_AUTHORIZE = 'NOA';
    const REQTYPE_PURCHASE  = 'CAA';

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'merchantId'    => '',
            'sign'          => '',
            'testMode'      => true,
            'returnMethod'  => [null, 'POST', 'GET'],
            'errorUrl'      => '',
            'language'     => [
                null, // account default
                'de', // German
                'en', // English
                'fr', // French
                'it', // Italian
                'es', // Spanish
                'el', // Greek
                'no', // Norwegian
                'da', // Danish
                'pl', // Polish
                'pt', // Portuguese
            ],
            'maskedCard' => true,
        ];
    }

    /**
     * @param $value
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * get the merchant id
     *
     * @return string
     */
    public function getMerchantId()
    {
        return  $this->getParameter('merchantId');
    }

    /**
     * @param array $options
     *
     * @return TokenizeRequest
     */
    public function createCard(array $options = array())
    {
        return $this->createRequest(TokenizeRequest::class, $options);
    }
}

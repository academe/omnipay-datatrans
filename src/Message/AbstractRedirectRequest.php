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

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Datatrans\Traits\HasGatewayParameters;
use Omnipay\Datatrans\Gateway;

abstract class AbstractRedirectRequest extends AbstractRequest
{
    use HasGatewayParameters;

    /**
     * @var string NOA or CAA (null to use account default)
     */
    protected $requestType;

    /**
     * @var array
     */
    protected $optionalParams = array(
    );

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign');

        $data = [
            'merchantId'    => $this->getMerchantId(),
            'refno'         => $this->getTransactionId(),
            'amount'        => $this->getAmountInteger(),
            'currency'      => $this->getCurrency(),
            'sign'          => $this->getSign(),
        ];

        if ($this->getReturnMethod()) {
            $data['uppWebResponseMethod'] = $this->getReturnMethod();
        }

        if ($this->getLanguage()) {
            $data['language'] = $this->getLanguage();
        }

        if ($this->requestType) {
            $data['reqtype'] = $this->requestType;
        }

        if ((bool) $this->getMaskedCard()) {
            $data['uppReturnMaskedCC'] = Gateway::RETURN_MASKED_CC;
        }

        if ((bool) $this->getCreateCard()) {
            $data['useAlias'] = Gateway::USE_ALIAS;
        }

        if ((bool) $this->getCreateCardAskUser()) {
            $data['uppRememberMe'] = Gateway::USE_ALIAS_ASK_USER;
        }

        if ($this->getPaymentMethod()) {
            $data['paymentmethod'] = $this->getPaymentMethod();
        }

        foreach ($this->optionalParams as $param) {
            $value = $this->getParameter($param);

            if ($value !== '') {
                $data[$param] = $value;
            }
        }

        // TODO: these are optional if set in the account.
        $data['successUrl'] = $this->getReturnUrl();
        $data['cancelUrl'] = $this->getCancelUrl();
        $data['errorUrl'] = $this->getErrorUrl();

        return $data;
    }

    /**
     * @return ResponseInterface
     */
    public function send()
    {
        return $this->sendData($this->getData());
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}

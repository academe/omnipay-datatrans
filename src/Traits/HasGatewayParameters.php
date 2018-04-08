<?php

namespace Omnipay\Datatrans\Traits;

/**
 * Setters and getters for parameters set at the Gateway level.
 */

use Omnipay\Datatrans\Gateway;

trait HasGatewayParameters
{
    /**
     * @var string
     */
    protected $requestType;

    /**
     * @param $value
     * @return $this
     */
    public function setRequestType($value)
    {
        return $this->setParameter('requestType', $value);
    }

    /**
     * Default to the request type defined in the request message, but allow
     * for an override for special requierments.
     *
     * @return string
     */
    public function getRequestType()
    {
        return $this->getParameter('requestType') ?: $this->requestType;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setSign($value)
    {
        return $this->setParameter('sign', $value);
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->getParameter('sign');
    }

    /**
     * @param $value
     * @return string
     */
    public function setReturnMethod($value)
    {
        return $this->setParameter('returnMethod', $value);
    }

    /**
     * @return string
     */
    public function getReturnMethod()
    {
        return $this->getParameter('returnMethod');
    }

    /**
     * @param $value
     * @return string
     */
    public function setErrorUrl($value)
    {
        return $this->setParameter('errorUrl', $value);
    }

    /**
     * @return string
     */
    public function getErrorUrl()
    {
        return $this->getParameter('errorUrl');
    }

    /**
     * @param $value
     * @return string
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param $value
     * @return mixed will be treated as boolean
     */
    public function setMaskedCard($value)
    {
        return $this->setParameter('maskedCard', $value);
    }

    /**
     * @return mixed
     */
    public function getMaskedCard()
    {
        return $this->getParameter('maskedCard');
    }

    /**
     * @param $value
     * @return mixed will be treated as boolean
     */
    public function setCreateCard($value)
    {
        return $this->setParameter('createCard', $value);
    }

    /**
     * @return mixed
     */
    public function getCreateCard()
    {
        return $this->getParameter('createCard');
    }

    /**
     * @param mixed $value will be treated as boolean
     * @return $this
     */
    public function setCreateCardAskUser($value)
    {
        return $this->setParameter('createCardAskUser', $value);
    }

    /**
     * @return mixed
     */
    public function getCreateCardAskUser()
    {
        return $this->getParameter('createCardAskUser');
    }

    /**
     * @param string three letter code
     * @return $this
     */
    public function setPaymentMethod($value)
    {
        return $this->setParameter('paymentMethod', $value);
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->getParameter('paymentMethod');
    }

    /**
     * CHECKME: I'm not convinced this is the full list. The Datatrans docs are
     * a little out of date in many places, often contradicting itself.
     *
     * @return boolean true if the payment method is a credit card.
     */
    public function paymentMethodIsCreditCard()
    {
        return in_array($this->getPaymentMethod(), [
            Gateway::PAYMENT_METHOD_VIS,
            Gateway::PAYMENT_METHOD_ECA,
            Gateway::PAYMENT_METHOD_AMX,
            Gateway::PAYMENT_METHOD_DIN,
            Gateway::PAYMENT_METHOD_DIS,
            Gateway::PAYMENT_METHOD_JCB,
        ]);
    }

    /**
     * @param string
     * @return $this
     */
    public function setErrorEmail($value)
    {
        return $this->setParameter('errorEmail', $value);
    }

    /**
     * @return string
     */
    public function getErrorEmail()
    {
        return $this->getParameter('errorEmail');
    }

    /**
     * The redieect method to use for the redirect mode payments.
     *
     * @param string $value POST or GET
     * @return $this
     */
    public function setRedirectMethod($value)
    {
        return $this->setParameter('redirectMethod', $value);
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return $this->getParameter('redirectMethod');
    }

    /**
     * @param string required for reqtype REF and REC
     * @return $this
     */
    public function setAcqAuthorizationCode($value)
    {
        return $this->setParameter('acqAuthorizationCode', $value);
    }

    /**
     * @return string
     */
    public function getAcqAuthorizationCode()
    {
        return $this->getParameter('acqAuthorizationCode');
    }

    /**
     * @param string HMAC key (in hexadecimal form) for outbound signing
     * @return $this
     */
    public function setHmacKey1($value)
    {
        return $this->setParameter('hmacKey1', $value);
    }

    /**
     * @return string
     */
    public function getHmacKey1()
    {
        return $this->getParameter('hmacKey1');
    }

    /**
     * @param string HMAC key (in hexadecimal form) for inbound signing
     * @return $this
     */
    public function setHmacKey2($value)
    {
        return $this->setParameter('hmacKey2', $value);
    }

    /**
     * @return string
     */
    public function getHmacKey2()
    {
        return $this->getParameter('hmacKey2');
    }

    /**
     * Returns the gateway signing HMAC key ('sign2', falling back to 'sign').
     * @return string
     */
    public function getHmacKey()
    {
        return $this->getParameter('hmacKey2') ?: $this->getParameter('hmacKey1');
    }
}

<?php

namespace Omnipay\Datatrans\Traits;

/**
 * Setters and getters for parameters set at the Gateway level.
 */

trait HasGatewayParameters
{
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

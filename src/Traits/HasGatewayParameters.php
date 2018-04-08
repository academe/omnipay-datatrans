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
    public function setInline($value)
    {
        return $this->setParameter('inline', $value);
    }

    /**
     * @return mixed
     */
    public function getInline()
    {
        return $this->getParameter('inline');
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
     * @return mixed
     */
    public function getHiddenMode()
    {
        return $this->getParameter('hiddenMode');
    }

    /**
     * @param $value
     * @return mixed will be treated as boolean
     */
    public function setHiddenMode($value)
    {
        return $this->setParameter('hiddenMode', $value);
    }

    /**
     * @return mixed
     */
    public function getForceRedirect()
    {
        return $this->getParameter('forceRedirect');
    }

    /**
     * @param $value
     * @return mixed will be treated as boolean
     */
    public function setForceRedirect($value)
    {
        return $this->setParameter('forceRedirect', $value);
    }

    /**
     * @return int used only for MFA payemnt method
     */
    public function getMfaReference()
    {
        return $this->getParameter('mfaReference');
    }

    /**
     * @param $value
     * @return int
     */
    public function setMfaReference($value)
    {
        return $this->setParameter('mfaReference', $value);
    }

    /**
     * @return string used only for ELC payemnt method
     */
    public function getRefno2()
    {
        return $this->getParameter('refno2');
    }

    /**
     * @param $value
     * @return string
     */
    public function setRefno2($value)
    {
        return $this->setParameter('refno2', $value);
    }

    /**
     * @return string used only for ELC payemnt method
     */
    public function getRefno3()
    {
        return $this->getParameter('refno3');
    }

    /**
     * @param $value
     * @return string
     */
    public function setRefno3($value)
    {
        return $this->setParameter('refno3', $value);
    }

    /**
     * @return string used only for MFG payemnt method
     */
    public function getVirtualCardno()
    {
        return $this->getParameter('virtualCardno');
    }

    /**
     * @param $value
     * @return string
     */
    public function setVirtualCardno($value)
    {
        return $this->setParameter('virtualCardno', $value);
    }

    /**
     * @return string URL to merchant terms and conditions
     */
    public function getTermsLink()
    {
        return $this->getParameter('uppTermsLink');
    }

    /**
     * @param $value
     * @return string
     */
    public function setTermsLink($value)
    {
        return $this->setParameter('uppTermsLink', $value);
    }

    /**
     * @return string
     */
    public function getStartTarget()
    {
        return $this->getParameter('uppStartTarget');
    }

    /**
     * @param $value
     * @return string
     */
    public function setStartTarget($value)
    {
        return $this->setParameter('uppStartTarget', $value);
    }

    /**
     * @return string
     */
    public function getReturnTarget()
    {
        return $this->getParameter('uppReturnTarget');
    }

    /**
     * @param $value
     * @return string
     */
    public function setReturnTarget($value)
    {
        return $this->setParameter('uppReturnTarget', $value);
    }

    /**
     * @return string
     */
    public function getCustomTheme()
    {
        return $this->getParameter('customTheme');
    }

    /**
     * @param $value
     * @return string
     */
    public function setCustomTheme($value)
    {
        return $this->setParameter('customTheme', $value);
    }

    /**
     * @return int
     */
    public function getDiscountAmount()
    {
        return $this->getParameter('uppDiscountAmount');
    }

    /**
     * @param $value
     * @return int
     */
    public function setDiscountAmount($value)
    {
        return $this->setParameter('uppDiscountAmount', $value);
    }

    /**
     * @return array
     */
    public function getCustomParameters()
    {
        return $this->getParameter('customParameters');
    }

    /**
     * @param $value
     * @return array
     */
    public function setCustomParameters($value)
    {
        return $this->setParameter('customParameters', $value);
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

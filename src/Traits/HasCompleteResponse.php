<?php

namespace Omnipay\Datatrans\Traits;

/**
 * Provides support for AcceptNotification and CompleteResponse,
 * which both read the final transaction results from the gateway.
 *
 * TODO: rename to HasResponseData
 */

use Omnipay\Datatrans\Gateway;
use Omnipay\Common\CreditCard;

trait HasCompleteResponse
{
    /**
     * @param string $name name of the data item
     * @param mixed the default value if the data item is not present
     * @return mixed
     */
    protected function getDataItem($name, $default = null)
    {
        if (array_key_exists($name, $this->getData())) {
            return $this->getData()[$name];
        }

        return $default;
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * Get the last 4 digits of the card number.
     *
     * @return string
     */
    public function getNumberLastFour()
    {
        return substr($this->getDataItem('maskedCC'), -4, 4) ?: null;
    }

    /**
     * Returns a masked credit card number with only the last 4 chars visible
     * Return an Omnipay format mask by default.
     * Set $mast to null to return the raw gateway masked card number.
     *
     * @param string $mask Character to use in place of numbers
     * @return string
     */
    public function getNumberMasked($mask = 'X')
    {
        $cardNumber = $this->getDataItem('maskedCC');

        if ($mask === null) {
            return $cardNumber;
        }

        $maskLength = strlen($cardNumber) - 4;
        return str_repeat($mask, $maskLength) . $this->getNumberLastFour();
    }

    /**
     * Get the card expiry month.
     *
     * @return int
     */
    public function getExpiryMonth()
    {
        return intval($this->getDataItem('expm'));
    }

    /**
     * Get the card expiry year.
     *
     * @return int
     */
    public function getExpiryYear()
    {
        return intval($this->getDataItem('expy'));
    }

    /**
     * Get the card expiry date, using the specified date format string.
     *
     * @param string $format
     * @return string
     */
    public function getExpiryDate($format)
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->getExpiryMonth(), 1, $this->getExpiryYear()));
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        $status = $this->getStatus();

        return $status === Gateway::STATUS_SUCCESS || $status === Gateway::STATUS_ACCEPTED;
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        $status = $this->getStatus();

        return $status === Gateway::STATUS_CANCEL;
    }

    /**
     * CHECKME: is the virtualCardno the same thing, but for specific payment methods?
     *
     * @return string|null the reusable card reference if requested by setting createCard
     */
    public function getCardReference()
    {
        return $this->getDataItem('aliasCC');
    }

    /**
     * Virtual card number for MFGroup Checkout
     */
    public function getVirtualCardno()
    {
        return $this->getDataItem('virtualCardno');
    }

    /**
     * DCC amount in home currency chosen by cardholder.
     * minorUnits
     */
    public function getDccAmount()
    {
        return $this->getDataItem('DccAmount');
    }

    /**
     * Cardholders home currency – ISO Character Code (USD, EUR etc.)
     */
    public function getDccCurrency()
    {
        return $this->getDataItem('DccCurrency');
    }

    /**
     * Applied exchange rate of DCC provider (e.g. 0.855304)
     */
    public function getDccRate()
    {
        return $this->getDataItem('DccRate');
    }

    /**
     * Get the payment method used.
     *
     * @return string
     */
    public function getUsedPaymentMethod()
    {
        return $this->getDataItem('pmethod') ?: $this->getDataItem('sourcepmethod');
    }

    /**
     * Authorization code returned by credit card issuing bank
     * (length depending on payment method).
     * The internal authorizationCode is deprecated and should not
     * be used now.
     *
     * @return srtring
     */
    public function getAuthorizationCode()
    {
        return $this->getDataItem('acqAuthorizationCode');
    }

    /**
     * @return strong ISO currency code.
     */
    public function getCurrencyCode()
    {
        return $this->getDataItem('currency');
    }

    /**
     * This leaves room for the Omnipay 3.x version will return a Money
     * object for getAmount()
     *
     * @return int the amount in minor units
     */
    public function getAmountMinorUnits()
    {
        return intval($this->getDataItem('amount'));
    }

    /**
     * Authorization response code. See docs for details.
     * @return string '01' or '02'
     */
    public function getResponseCode()
    {
        return $this->getDataItem('responseCode');
    }

    public function getErrorDetail()
    {
        return $this->getDataItem('errorDetail');
    }

    /**
     * @return bool true if the original request was an authorize only
     */
    public function isAuthorize()
    {
        return $this->getDataItem('reqtype') === Gateway::REQTYPE_AUTHORIZE;
    }

    /**
     * @return bool true if the original request was a purchase (authorize+clearing)
     */
    public function isPurchase()
    {
        return $this->getDataItem('reqtype') === Gateway::REQTYPE_PURCHASE;
    }

    /**
     * @return bool true if the original request was a void
     */
    public function isVoid()
    {
        return $this->getDataItem('reqtype') === Gateway::REQTYPE_DOA;
    }

    /**
     * @return bool true if the original request was a capture
     */
    public function isCapture()
    {
        return $this->getDataItem('reqtype') === Gateway::REQTYPE_COA;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getDataItem('errorMessage') ?: $this->getDataItem('responseMessage');
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getDataItem('refno', '');
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->getDataItem('uppTransactionId', '');
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->getDataItem('status');
    }

    /**
     * Constant "web" for successUrl, "post" for postUrl.
     *
     * @return mixed
     */
    public function getMessageType()
    {
        return $this->getDataItem('uppMsgType');
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->getDataItem('errorCode');
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->getErrorCode() ?: $this->getDataItem('responseCode');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getAvailableCredit()
    {
        return $this->getDataItem('mfaAvailableCredit');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getMaximalCredit()
    {
        return $this->getDataItem('mfaMaximalCredit');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getReference()
    {
        return $this->getDataItem('mfaReference');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getCreditRefusalReason()
    {
        return $this->getDataItem('mfaCreditRefusalReason');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getMfaResponseCode()
    {
        return $this->getDataItem('mfaResponseCode');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getInstallmentNumber()
    {
        return $this->getDataItem('installmentNumber');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getInstallmentAmount()
    {
        return $this->getDataItem('installmentAmount');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getInstallmentFees()
    {
        return $this->getDataItem('installmentFees');
    }

    /**
     * @return mixed MFA specific parameter
     */
    public function getInstallmentInterests()
    {
        return $this->getDataItem('installmentInterests');
    }

    /**
     * @return mixed MFA specific parameter (XML document)
     */
    public function getESRData()
    {
        return $this->getDataItem('ESRData');
    }

    /**
     * @return mixed MFA specific parameter (XML document)
     */
    public function getBankConnection()
    {
        return $this->getDataItem('BankConnection');
    }

    /**
     * @return mixed MFA specific parameter (XML document)
     */
    public function getPendingPayPal()
    {
        return $this->getDataItem('pendingPayPal');
    }

    /**
     * @return CreditCard captured biling and/or shipping details.
     * @todo also capture here any credit card details (masked number, expiry dates)
     * that we are given.
     */
    public function getCard()
    {
        $data = [];

        // Billing details.

        if ($this->getDataItem('uppCustomerTitle')) {
            $data['billingTitle'] = $this->getDataItem('uppCustomerTitle');
        }

        if ($this->getDataItem('uppCustomerName')) {
            $data['billingName'] = $this->getDataItem('uppCustomerName');
        }

        if ($this->getDataItem('uppCustomerFirstName')) {
            $data['billingFirstName'] = $this->getDataItem('uppCustomerFirstName');
        }

        if ($this->getDataItem('uppCustomerLastName')) {
            $data['billingLastName'] = $this->getDataItem('uppCustomerLastName');
        }

        if ($this->getDataItem('uppCustomerStreet')) {
            $data['billingAddress1'] = $this->getDataItem('uppCustomerStreet');
        }

        if ($this->getDataItem('uppCustomerStreet2')) {
            $data['billingAddress2'] = $this->getDataItem('uppCustomerStreet2');
        }

        if ($this->getDataItem('uppCustomerCity')) {
            $data['billingCity'] = $this->getDataItem('uppCustomerCity');
        }

        if ($this->getDataItem('uppCustomerCountry')) {
            $data['billingCountry'] = $this->getDataItem('uppCustomerCountry');
        }

        if ($this->getDataItem('uppCustomerZipCode')) {
            $data['billingPostcode'] = $this->getDataItem('uppCustomerZipCode');
        }

        if ($this->getDataItem('uppCustomerEmail')) {
            $data['email'] = $this->getDataItem('uppCustomerEmail');
        }

        if ($this->getDataItem('uppCustomerGender')) {
            $data['gender'] = $this->getDataItem('uppCustomerGender');
        }

        if ($this->getDataItem('uppCustomerBirthDate')) {
            $data['birthday'] = $this->getDataItem('uppCustomerBirthDate');
        }

        if ($this->getDataItem('uppCustomerPhone')) {
            $data['billingPhone'] = $this->getDataItem('uppCustomerPhone');
        }

        if ($this->getDataItem('uppCustomerFax')) {
            $data['billingFax'] = $this->getDataItem('uppCustomerFax');
        }

        // Shipping details.

        if ($this->getDataItem('uppShippingTitle')) {
            $data['ShippingTitle'] = $this->getDataItem('uppShippingTitle');
        }

        if ($this->getDataItem('uppShippingName')) {
            $data['ShippingName'] = $this->getDataItem('uppShippingName');
        }

        if ($this->getDataItem('uppShippingFirstName')) {
            $data['ShippingFirstName'] = $this->getDataItem('uppShippingFirstName');
        }

        if ($this->getDataItem('uppShippingLastName')) {
            $data['ShippingLastName'] = $this->getDataItem('uppShippingLastName');
        }

        if ($this->getDataItem('uppShippingStreet')) {
            $data['ShippingAddress1'] = $this->getDataItem('uppShippingStreet');
        }

        if ($this->getDataItem('uppShippingStreet2')) {
            $data['ShippingAddress2'] = $this->getDataItem('uppShippingStreet2');
        }

        if ($this->getDataItem('uppShippingCity')) {
            $data['ShippingCity'] = $this->getDataItem('uppShippingCity');
        }

        if ($this->getDataItem('uppShippingCountry')) {
            $data['ShippingCountry'] = $this->getDataItem('uppShippingCountry');
        }

        if ($this->getDataItem('uppShippingZipCode')) {
            $data['ShippingPostcode'] = $this->getDataItem('uppShippingZipCode');
        }

        if ($this->getDataItem('uppShippingPhone')) {
            $data['ShippingPhone'] = $this->getDataItem('uppShippingPhone');
        }

        if ($this->getDataItem('uppShippingFax')) {
            $data['ShippingFax'] = $this->getDataItem('uppShippingFax');
        }

        return new CreditCard($data);
    }
}

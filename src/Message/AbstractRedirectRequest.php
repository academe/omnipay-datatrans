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
 * The abstract request for redirect requests.
 * These involve sending the end user directly to the remote gateway
 * as the very first step.
 */

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Datatrans\Traits\HasGatewayParameters;
use Omnipay\Datatrans\Gateway;

abstract class AbstractRedirectRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign');

        $data = [
            'merchantId'        => $this->getMerchantId(),
            'amount'            => $this->getAmountInteger(),
            'currency'          => $this->getCurrency(),
            'refno'             => $this->getTransactionId(),
        ];

        // The kind of redirect the merchant site would like.

        if ($this->getRedirectMethod()) {
            $data['redirectMethod'] = $this->getRedirectMethod();
        }

        // If the amount is zero, then the merchant site is seeking
        // authorisation for the card (or other payment method) only.
        // Some docuemnts list using '1' instead of zero, but both seem
        // to work.

        if ($this->getAmountInteger() === 0) {
            $data['uppAliasOnly'] = Gateway::CARD_ALIAS_ONLY;
        }

        if ($card = $this->getCard()) {
            // The card alias could be set, with optional expiry date.

            if ($card->getExpiryMonth()) {
                $data['expm'] = $card->getExpiryMonth();
                $data['expy'] = $card->getExpiryDate('y');
                $data['aliasCC'] = $card->getNumber();
            }

            // Hidden mode has PCI requirements when used, since the merchant
            // site will be handling the CVV entered by the user.
            // Better not to use it. But it's here for more complete support.

            if ($this->getHiddenMode()) {
                if ($card->getCvv()) {
                    $data['hiddenMode'] = 'yes';
                    $data['cvv'] = $card->getCvv();
                }
            }
        }

        // The card reference be provided without a card object and without
        // an expiry date.
        if ($this->getCardReference()) {
            $data['aliasCC'] = $this->getCardReference();
        }

        if ($this->getReturnMethod()) {
            $data['uppWebResponseMethod'] = $this->getReturnMethod();
        }

        if ($this->getLanguage()) {
            $data['language'] = $this->getLanguage();
        }

        if ($this->getTermsLink()) {
            $data['uppTermsLink'] = $this->getTermsLink();
        }

        if ($this->getStartTarget()) {
            $data['uppStartTarget'] = $this->getStartTarget();
        }

        if ($this->getReturnTarget()) {
            $data['uppReturnTarget'] = $this->getReturnTarget();
        }

        if ($this->getCustomTheme()) {
            $data['customTheme'] = $this->getCustomTheme();
        }

        if ($this->getForceRedirect()) {
            $data['mode'] = 'forceRedirect';
        }

        // When using PayPal, always ask for a copy of the address entered.
        // This will work for PayPal Express only.

        if ($this->getPaymentMethod() === Gateway::PAYMENT_METHOD_PAP) {
            $data['uppCustomerDetails'] = 'return';
        }

        if ($this->getInline()) {
            $data['theme'] = 'Inline';
        }

        // The Discount Amount is in minor units.
        // A Money\Money object would be better for Omnipay 3.x

        if ($this->getDiscountAmount() !== null) {
            $data['uppDiscountAmount'] = $this->getDiscountAmount();
        }

        // An array of custom parameters can be included.

        if ($this->getCustomParameters()) {
            $data = array_merge($data, $this->getCustomParameters());
        }

        if ($this->requestType) {
            $data['reqtype'] = $this->requestType;
        }

        if ($this->getThemeConfiguration()) {
            $data['themeConfiguration'] = $this->getThemeConfiguration();
        }

        if ((bool) $this->getMaskedCard()) {
            $data['uppReturnMaskedCC'] = Gateway::RETURN_MASKED_CC;
        }

        if ((bool) $this->getCreateCard()) {
            // The PayPal equivalent to this is to create an order that can
            // be used repeatedly to authorise payments against.

            if ($this->getPaymentMethod() === Gateway::PAYMENT_METHOD_PAP) {
                $this->setPayPalOrderId(true);
            } else {
                $data['useAlias'] = Gateway::USE_ALIAS;
            }
        }

        if ((bool) $this->getCreateCardAskUser()) {
            $data['uppRememberMe'] = Gateway::USE_ALIAS_ASK_USER;
        }

        if ($this->getPaymentMethod()) {
            // 'method' must be lower-case to be recognised by the gateway.
            // Some documentation examples show this as lowerCamelCase, but
            // that is incorrect.

            $data['paymentmethod'] = $this->getPaymentMethod();
        }

        // Add the optional customer details.

        $data = $this->getCustomerData($data);

        // Additional parameters for specific payment types.

        switch ($this->getPaymentMethod()) {
            case Gateway::PAYMENT_METHOD_PAP:
                // Paypal
                $data = $this->extraParamsPAP($data);
                break;
            case Gateway::PAYMENT_METHOD_PEF:
                // Swiss PostFinance E-Finance
                $data = $this->extraParamsPEF($data);
                break;
            case Gateway::PAYMENT_METHOD_PFC:
                // Swiss PostFinance Card
                $data = $this->extraParamsPFC($data);
                break;
            case Gateway::PAYMENT_METHOD_MFA:
                // MFGroup Check Out (Credit Check)
                $data = $this->extraParamsMFA($data);
                break;
            case Gateway::PAYMENT_METHOD_ELV:
                // SEPA Direct Debit / ELV
                $data = $this->extraParamsELV($data);
                break;
            case Gateway::PAYMENT_METHOD_MFG:
                // MFGroup Financial Request (authorization)
                $data = $this->extraParamsMFG($data);
                break;
            case Gateway::PAYMENT_METHOD_CUR:
                // Curabill
                $data = $this->extraParamsCUR($data);
                break;
            case Gateway::PAYMENT_METHOD_PYO:
                // Payolution
                $data = $this->extraParamsPYO($data);
                break;
        }

        // Sign the message.

        $data['sign'] = $this->getSigning();

        // These URLs are optional here, if set in the account.

        if ($this->getReturnUrl() !== null) {
            $data['successUrl'] = $this->getReturnUrl();
        }

        if ($this->getCancelUrl() !== null) {
            $data['cancelUrl'] = $this->getCancelUrl();
        }

        if ($this->getErrorUrl() !== null) {
            $data['errorUrl'] = $this->getErrorUrl();
        }

        return $data;
    }

    /**
     * Populate the data array with any customer details supplied in the card.
     */
    public function getCustomerData(array $data = [])
    {
        if (! $card = $this->getCard()) {
            return $data;
        }

        $customer = [];

        if ($card->getTitle()) {
            $customer['uppCustomerTitle'] = $card->getTitle();
        }

        if ($card->getName()) {
            $customer['uppCustomerName'] = $card->getName();
        }

        if ($card->getFirstName()) {
            $customer['uppCustomerFirstName'] = $card->getFirstName();
        }

        if ($card->getLastName()) {
            $customer['uppCustomerLastName'] = $card->getLastName();
        }

        if ($card->getAddress1()) {
            $customer['uppStreet'] = $card->getAddress1();
        }

        if ($card->getAddress2()) {
            $customer['uppStreet2'] = $card->getAddress2();
        }

        if ($card->getCity()) {
            $customer['uppCity'] = $card->getCity();
        }

        if ($card->getCountry() && preg_match('/[A-Z]{3}/', $card->getCountry())) {
            $customer['uppCountry'] = $card->getCountry();
        }

        if ($card->getPostcode()) {
            $customer['uppCustomerZipCode'] = $card->getPostcode();
        }

        if ($card->getState()) {
            $customer['uppState'] = $card->getState();
        }

        if ($card->getPhone()) {
            $customer['uppPhone'] = $card->getPhone();
        }

        if ($card->getFax()) {
            $customer['uppFax'] = $card->getFax();
        }

        if ($card->getEmail()) {
            $customer['uppCustomerEmail'] = $card->getEmail();
        }

        if ($card->getGender()) {
            $customer['uppCustomerGender'] = strtoupper(substr($card->getGender(), 0, 1)) === 'M'
                ? Gateway::GENDER_MALE
                : Gateway::GENDER_FEMALE;
        }

        // API requires format "dd.mm.yyyy" or "yyyy-mm-dd"

        if ($card->getBirthday()) {
            $customer['uppCustomerBirthDate'] = $card->getBirthday('Y-m-d');
        }

        if (count($customer)) {
            $data = array_merge($data, $customer);

            if (empty($data['uppCustomerDetails'])) {
                $data['uppCustomerDetails'] = 'yes';
            }
        }

        return $data;
    }

    /**
     * Additional parameters for PayPal (PAP).
     */
    protected function extraParamsPAP(array $data = [])
    {
        // TODO: basket details (MUST add up correctly)

        if ($this->getCartBorderColor()) {
            $data['cpp-cart-border-color'] = $this->getCartBorderColor();
        }

        if ($this->getPayPalAllowNote()) {
            $data['PayPalAllowNote'] = $this->getPayPalAllowNote();
        }

        if ($this->getForwardCustomerDetails() !== null) {
            $data['uppForwardCustomerDetails'] = (bool)$this->getForwardCustomerDetails()
                ? 'yes'
                : 'no';
        }

        if ($this->getDisplayShippingDetails() !== null) {
            $data['uppDisplayShippingDetails'] = (bool)$this->getDisplayShippingDetails()
                ? 'yes'
                : 'no';
        }

        if ($this->getPPPayerID()) {
            $data['ppPayerID'] = $this->getPPPayerID();
        }

        if ((bool)$this->getPayPalOrderId()) {
            $data['PayPalOrderId'] = 'get';
        }

        // Add the basket to PayPal.

        $data = $this->getPapBasket($data);

        return $data;
    }

    /**
     * Get the basket/card/items into a PayPal format.
     * @param array $data
     * @return array The data with the ItemBag added if set.
     */
    public function getPapBasket(array $data = [])
    {
        if (empty($this->getItems())) {
            return $data;
        }

        // Some running totals need to be maintained.
        // Amounts are sent to PayPal as minor units.
        // The standard Omnipay Item does not support separate tax or shipping values,
        // and these will be defaulted to zero when an extended basket is not used.

        $itemsAmount = 0;
        $taxAmount = 0;
        $shippingAmount = 0;

        $itemData = [];
        $itemNumber  = 0;

        foreach ($this->getItems() as $item) {
            $itemPrice = $this->priceMinorUnit($item->getPrice());
            $itemQuantity = $item->getQuantity();

            $itemData['L_NAME' . $itemNumber] = $item->getName();
            $itemData['L_AMT' . $itemNumber] = $itemPrice;
            $itemData['L_Number' . $itemNumber] = $itemNumber + 1;
            $itemData['L_Desc' . $itemNumber] = $item->getDescription();
            $itemData['L_QTY' . $itemNumber] = $itemQuantity;
            $itemData['L_TAXAMT' . $itemNumber] = 0;

            $itemsAmount += $itemPrice * $itemQuantity;

            $itemNumber++;
        }

        $data['ITEMAMT'] = $itemsAmount;
        $data['TAXAMT'] = $taxAmount;
        $data['SHIPPINGAMT'] = $shippingAmount;

        $data = array_merge($data, $itemData);

        return $data;
    }

    /**
     * Convert an Item price to minor units
     * @param mixed $price
     * @return int
     */
    protected function priceMinorUnit($price)
    {
        if ($price instanceof Money) {
            // Money::EUR(123)
            return (int)$price->getAmount();
        } elseif (is_int($price)) {
            // 123
            return $price;
        } elseif (is_float($price)) {
            // 1.23
            return (int)($price * 100); // Horrible hack! Needs to take currency into account.
        } elseif (is_string($price) && strpos($price, '.') !== false) {
            // '1.23'
            return (int)((float)$price * 100); // Horrible hack!
        } elseif (is_string($price)) {
            // '123'
            return (int)$price;
        }

        return 0;
    }

    /**
     * Additional parameters for Swiss PostFinance E-Finance (PEF).
     */
    protected function extraParamsPEF(array $data = [])
    {
        return $data;
    }

    /**
     * Additional parameters for Swiss PostFinance Card (PFC).
     */
    protected function extraParamsPFC(array $data = [])
    {
        return $data;
    }

    /**
     * Additional parameters for MFGroup Check Out (Credit Check) (MFA).
     */
    protected function extraParamsMFA(array $data = [])
    {
        if ($this->getMfaReference()) {
            $data['mfaReference'] = $this->getMfaReference();
        }

        return $data;
    }

    /**
     * Additional parameters for Curabill (CUR).
     */
    protected function extraParamsCUR(array $data = [])
    {
        // The XML document is base64 encoded before sending.

        if ($this->getMfaReference()) {
            $data['curabillDocument'] = base64_encode($this->getCurabillDocument());
        }

        return $data;
    }

    /**
     * Additional parameters for Payolution (PYO).
     * TODO: uppCustomerAirlineDeparture uppCustomerAirlineRoute uppCustomerAirlineFlightNumber
     * uppCustomerAirlineBookingCode uppCustomerAirlineFrequentFlyer
     */
    protected function extraParamsPYO(array $data = [])
    {
        // TODO: plus most customer details are also mandatory.
        $this->validate('customerType');

        $data['uppCustomerType'] = $this->getCustomerType();

        return $data;
    }

    /**
     * Additional parameters for SEPA Direct Debit / ELV (ELV).
     */
    protected function extraParamsELV(array $data = [])
    {
        if ($this->getRefno2()) {
            $data['refno2'] = $this->getRefno2();
        }

        if ($this->getRefno3()) {
            $data['refno3'] = $this->getRefno3();
        }

        $data['uppElvMandatText'] = $this->getElvMandatText();
        $data['bankiban'] = $this->getBankIban();
        $data['bankbic'] = $this->getBankBic();
        $data['elvMandateID'] = $this->getElvMandateID();

        if ($this->getPurchaseType()) {
            $data['PurchaseType'] = $this->getPurchaseType();
        }

        return $data;
    }

    /**
     * Additional parameters for MFGroup Financial Request (authorization) (MFG).
     * TODO: about a dozen more parameters, but the documentation is a little unclear.
     */
    protected function extraParamsMFG(array $data = [])
    {
        if ($this->getVirtualCardno()) {
            $data['virtualCardno'] = $this->getVirtualCardno();
        }

        if ($this->getRefno2()) {
            $data['refno2'] = $this->getRefno2();
        }

        if ($this->getRefno3()) {
            $data['refno3'] = $this->getRefno3();
        }

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
        return $this->response = new RedirectResponse($this, $data);
    }
}

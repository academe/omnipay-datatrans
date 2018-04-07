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
     * Transaction cancel request
     */
    const REQTYPE_DOA = 'DOA';

    /**
     * Settlement Debit/Credit
     */
    const REQTYPE_COA = 'COA';

    /**
     * Transaction status request (simple data, default for the gateway)
     */
    const REQTYPE_STA = 'STA';

    /**
     * Transaction status request (extended data, default for this driver)
     */
    const REQTYPE_STX = 'STX';

    /**
     * Re-Authorization of old transaction
     */
    const REQTYPE_REA = 'REA';

    /**
     * Submission of acqAuthorizationCode after denial
     */
    const REQTYPE_REC = 'REC';

    /**
     * Submission of acqAuthorizationCode after referral
     */
    const REQTYPE_REF = 'REF';

    /**
     * Debit Transaction
     */
    const TRANSACTION_TYPE_DEBIT = '05';

    /**
     * Credit Transaction
     */
    const TRANSACTION_TYPE_CREDIT = '06';

    /**
     * @var string return method values
     */
    const RETURN_METHOD_GET     = 'GET';
    const RETURN_METHOD_POST    = 'POST';

    /**
     * @var string value to request a masked CC number is retuned
     */
    const RETURN_MASKED_CC = 'yes';

    /**
     * @var string value to request that a CC alias is retuned
     */
    const USE_ALIAS = 'yes';

    /**
     * @var string value to request that the user is asked to confirm a CC alias is retuned
     */
    const USE_ALIAS_ASK_USER = 'yes';

    /**
     * @var string value to request a card alias only (uppAliasOnly data item)
     */
    const CARD_ALIAS_ONLY = 'yes';

    /**
     * Supported payment types.
     */
    const PAYMENT_TYPE_VIS = 'VIS'; // VISA
    const PAYMENT_TYPE_ECA = 'ECA'; // MasterCard
    const PAYMENT_TYPE_AMX = 'AMX'; // American Express
    const PAYMENT_TYPE_BPY = 'BPY'; // Billpay
    const PAYMENT_TYPE_CUP = 'CUP'; // China Union Pay
    const PAYMENT_TYPE_DIN = 'DIN'; // Diners Club
    const PAYMENT_TYPE_DIS = 'DIS'; // Discover
    const PAYMENT_TYPE_DEA = 'DEA'; // * iDeal
    const PAYMENT_TYPE_DIB = 'DIB'; // SOFORT Überweisung
    const PAYMENT_TYPE_DII = 'DII'; // iDEAL via SOFORT Überweisung
    const PAYMENT_TYPE_DNK = 'DNK'; // Dankort
    const PAYMENT_TYPE_DVI = 'DVI'; // Deltavista
    const PAYMENT_TYPE_ELV = 'ELV'; // SEPA Direct Debit / ELV
    const PAYMENT_TYPE_ESY = 'ESY'; // * Swisscom Easypay
    const PAYMENT_TYPE_JCB = 'JCB'; // JCB
    const PAYMENT_TYPE_JEL = 'JEL'; // Jelmoli Bonus Card
    const PAYMENT_TYPE_MAU = 'MAU'; // Maestro
    const PAYMENT_TYPE_MDP = 'MDP'; // Migros Bank Payment
    const PAYMENT_TYPE_MFA = 'MFA'; // MFGroup Check Out (Credit Check)
    const PAYMENT_TYPE_MFG = 'MFG'; // MFGroup Financial Request (authorization)
    const PAYMENT_TYPE_MFX = 'MFX'; // MFGroup Easy integration
    const PAYMENT_TYPE_MMS = 'MMS'; // Mediamarkt Shopping Card
    const PAYMENT_TYPE_MNB = 'MNB'; // * Moneybookers only with reqtype CAA
    const PAYMENT_TYPE_MYO = 'MYO'; // Manor MyOne Card
    const PAYMENT_TYPE_PAP = 'PAP'; // * PayPal
    const PAYMENT_TYPE_PEF = 'PEF'; // * Swiss PostFinance E-Finance
    const PAYMENT_TYPE_PFC = 'PFC'; // * Swiss PostFinance Card
    const PAYMENT_TYPE_PSC = 'PSC'; // * Paysafecard
    const PAYMENT_TYPE_PYL = 'PYL'; // Payolution Installments
    const PAYMENT_TYPE_PYO = 'PYO'; // Payolution Invoice
    const PAYMENT_TYPE_REK = 'REK'; // Reka Card
    const PAYMENT_TYPE_SWB = 'SWB'; // SwissBilling
    const PAYMENT_TYPE_TWI = 'TWI'; // * TWINT Wallet
    const PAYMENT_TYPE_MPW = 'MPW'; // * MasterPass Wallet
    const PAYMENT_TYPE_ACC = 'ACC'; // * Accarda Kauf
    const PAYMENT_TYPE_INT = 'INT'; // * Byjuno
    const PAYMENT_TYPE_PPA = 'PPA'; // * LoyLogic Pointspay
    const PAYMENT_TYPE_GPA = 'GPA'; // * Girosolution Giropay
    const PAYMENT_TYPE_GEP = 'GEP'; // * Girosolution EPS
    const PAYMENT_TYPE_BON = 'BON'; // Boncard

    /**
     * "status" values.
     */
    const STATUS_SUCCESS    = 'success';
    const STATUS_ACCEPTED   = 'accepted';
    const STATUS_ERROR      = 'error';
    const STATUS_CANCEL     = 'cancel';

    /**
     * The XML service version.
     */
    const XML_SERVICE_VERSION = '3';

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'merchantId'    => '',
            'sign'          => '',
            'testMode'      => true,
            'returnMethod'  => [
                null,
                self::RETURN_METHOD_POST,
                self::RETURN_METHOD_GET,
            ],
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
            'createCard' => false,
            'createCardAskUser' => false,
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

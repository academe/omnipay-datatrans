<?php

namespace Omnipay\Datatrans\Interfaces;

interface Constants
{
    /**
     * @var string authorize only
     */
    const REQTYPE_AUTHORIZE = 'NOA';

    /**
     * @var string authorize and settle immediately
     */
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
     * @deprec No longer used.
     */
    const CARD_ALIAS_ONLY = 'yes';

    /**
     * Supported payment types.
     */
    const PAYMENT_METHOD_VIS = 'VIS'; // VISA
    const PAYMENT_METHOD_ECA = 'ECA'; // MasterCard
    const PAYMENT_METHOD_AMX = 'AMX'; // American Express
    const PAYMENT_METHOD_DIN = 'DIN'; // Diners Club
    const PAYMENT_METHOD_JCB = 'JCB'; // JCB

    const PAYMENT_METHOD_BPY = 'BPY'; // Billpay
    const PAYMENT_METHOD_CUP = 'CUP'; // China Union Pay
    const PAYMENT_METHOD_CUR = 'CUR'; // Curabill
    const PAYMENT_METHOD_DIS = 'DIS'; // Discover
    const PAYMENT_METHOD_DEA = 'DEA'; // * iDeal
    const PAYMENT_METHOD_DIB = 'DIB'; // SOFORT Überweisung
    const PAYMENT_METHOD_DII = 'DII'; // iDEAL via SOFORT Überweisung
    const PAYMENT_METHOD_DNK = 'DNK'; // Dankort
    const PAYMENT_METHOD_DVI = 'DVI'; // Deltavista
    const PAYMENT_METHOD_ELV = 'ELV'; // SEPA Direct Debit / ELV
    const PAYMENT_METHOD_ESY = 'ESY'; // * Swisscom Easypay
    const PAYMENT_METHOD_JEL = 'JEL'; // Jelmoli Bonus Card
    const PAYMENT_METHOD_MAU = 'MAU'; // Maestro
    const PAYMENT_METHOD_MDP = 'MDP'; // Migros Bank Payment
    const PAYMENT_METHOD_MFA = 'MFA'; // MFGroup Check Out (Credit Check)
    const PAYMENT_METHOD_MFG = 'MFG'; // MFGroup Financial Request (authorization)
    const PAYMENT_METHOD_MFX = 'MFX'; // MFGroup Easy integration
    const PAYMENT_METHOD_MMS = 'MMS'; // Mediamarkt Shopping Card
    const PAYMENT_METHOD_MNB = 'MNB'; // * Moneybookers only with reqtype CAA
    const PAYMENT_METHOD_MYO = 'MYO'; // Manor MyOne Card
    const PAYMENT_METHOD_PAP = 'PAP'; // * PayPal
    const PAYMENT_METHOD_PEF = 'PEF'; // * Swiss PostFinance E-Finance
    const PAYMENT_METHOD_PFC = 'PFC'; // * Swiss PostFinance Card
    const PAYMENT_METHOD_PSC = 'PSC'; // * Paysafecard
    const PAYMENT_METHOD_PYL = 'PYL'; // Payolution Installments
    const PAYMENT_METHOD_PYO = 'PYO'; // Payolution Invoice
    const PAYMENT_METHOD_REK = 'REK'; // Reka Card
    const PAYMENT_METHOD_SWB = 'SWB'; // SwissBilling
    const PAYMENT_METHOD_TWI = 'TWI'; // * TWINT Wallet
    const PAYMENT_METHOD_MPW = 'MPW'; // * MasterPass Wallet
    const PAYMENT_METHOD_ACC = 'ACC'; // * Accarda Kauf
    const PAYMENT_METHOD_INT = 'INT'; // * Byjuno
    const PAYMENT_METHOD_PPA = 'PPA'; // * LoyLogic Pointspay
    const PAYMENT_METHOD_GPA = 'GPA'; // * Girosolution Giropay
    const PAYMENT_METHOD_GEP = 'GEP'; // * Girosolution EPS
    const PAYMENT_METHOD_BON = 'BON'; // Boncard

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
     * UPP and XML error codes.
     */

    // required parameter missing
    const ERROR_CODE_1001 = '1001';
    // format of parameter is not valid
    const ERROR_CODE_1002 = '1002';
     // value not found
    const ERROR_CODE_1003 = '1003';
     // card number is not valid
    const ERROR_CODE_1004 = '1004';
     // card expired
    const ERROR_CODE_1006 = '1006';
     // access denied by sign control
    const ERROR_CODE_1007 = '1007';
     // access disabled by admin
    const ERROR_CODE_1008 = '1008';
     // merchant paym.method init error
    const ERROR_CODE_1009 = '1009';
     // action not allowed (not applicable transaction status)
    const ERROR_CODE_1010 = '1010';
     // duplicate settlement request
    const ERROR_CODE_1012 = '1012';
     // transaction declined without any further reason
    const ERROR_CODE_1403 = '1403';

    /**
     * XML error codes.
     */

    // Access denied by protocol control
    const ERROR_CODE_2000 = '2000';
    // Input document missing
    const ERROR_CODE_2001 = '2001';
    // Error building document
    const ERROR_CODE_2002 = '2002';
    // Root element invalid
    const ERROR_CODE_2011 = '2011';
    // Body element missing
    const ERROR_CODE_2012 = '2012';
    // merchantId missing
    const ERROR_CODE_2013 = '2013';
    // Element missing
    const ERROR_CODE_2014 = '2014';
    // Missing value
    const ERROR_CODE_2021 = '2021';
    // Invalid value
    const ERROR_CODE_2022 = '2022';
    // offline authorization not allowed
    const ERROR_CODE_2031 = '2031';
    // 3D-Directory request not started
    const ERROR_CODE_2041 = '2041';
    // 3D-Directory request not finished
    const ERROR_CODE_2042 = '2042';
    // 3D-ACS process not started
    const ERROR_CODE_2043 = '2043';
    // 3D-ACS process not finished
    const ERROR_CODE_2044 = '2044';
    // initialization UPP record not found
    const ERROR_CODE_2051 = '2051';
    // internal error
    const ERROR_CODE_2097 = '2097';
    // Database error
    const ERROR_CODE_2098 = '2098';
    // XML processing error
    const ERROR_CODE_2099 = '2099';

    /**
     * Anti-fraud error codes
     */

    // IP address declined by global fraud mgmt.
    const ERROR_CODE_3001 = '3001';
    // IP address declined by merchant fraud mgmt.
    const ERROR_CODE_3002 = '3002';
    // CC number declined by global fraud mgmt.
    const ERROR_CODE_3003 = '3003';
    // CC number declined by merchant fraud mgmt.
    const ERROR_CODE_3004 = '3004';
    // IP address declined by group fraud mgmt.
    const ERROR_CODE_3005 = '3005';
    // CC number declined by group fraud mgmt.
    const ERROR_CODE_3006 = '3006';
    // declined by merchant fraud mgmt. - TRX per IP
    const ERROR_CODE_3011 = '3011';
    // declined by group fraud mgmt. - TRX per IP
    const ERROR_CODE_3012 = '3012';
    // declined by merchant fraud mgmt. - TRX per CC
    const ERROR_CODE_3013 = '3013';
    // declined by group fraud mgmt. - TRX per CC
    const ERROR_CODE_3014 = '3014';
    // declined by merchant fraud mgmt. - AMOUNT per CC
    const ERROR_CODE_3015 = '3015';
    // declined by group fraud mgmt. - AMOUNT per CC
    const ERROR_CODE_3016 = '3016';
    // declined by country filter - Unknown BIN/Country
    const ERROR_CODE_3021 = '3021';
    // country declined by country filter
    const ERROR_CODE_3022 = '3022';
    // declined by country verification - uppCustomerCountry missing
    const ERROR_CODE_3023 = '3023';
    // declined by country verification - country does not match
    const ERROR_CODE_3024 = '3024';
    // country declined by group country filter
    const ERROR_CODE_3025 = '3025';
    // declined due to response code 02
    const ERROR_CODE_3031 = '3031';
    // declined due to postPage response error
    const ERROR_CODE_3041 = '3041';
    // declined due to country verification check
    const ERROR_CODE_3051 = '3051';
    // declined due to unique refno check
    const ERROR_CODE_3061 = '3061';
    // declined due to AVS check
    const ERROR_CODE_3071 = '3071';

    /**
     * Alias error codes.
     */

    /**
     * CC alias update error
     */
    const ERROR_CODE_ALIAS_UPDATE_ERROR = '-885';

    /**
     * CC alias insert error
     */
    const ERROR_CODE_ALIAS_INSERT_ERROR = '-886';

    /**
     * CC alias does not match with cardno
     */
    const ERROR_CODE_ALIAS_CARD_NO = '-887';

    /**
     * CC alias not found
     */
    const ERROR_CODE_ALIAS_NOT_FOUND = '-888';

    /**
     * CC alias error / input parameters missing
     */
    const ERROR_CODE_ALIAS_ERROR = '-889';

    /**
     * CC alias service is not supported
     */
    const ERROR_CODE_ALIAS_SERVICE_NOT_SUPPORTED = '-900';

    /**
     * generel error
     */
    const ERROR_CODE_ALIAS_GENEREL_ERROR = '-999';

    /**
     * @var string Gender values
     */
    const GENDER_MALE   = 'male';
    const GENDER_FEMALE = 'female';

    /**
     * @var string Message type values
     *
     * "web" for successUrl, "post" for postUrl.
     */
    const MESSAGE_TYPE_WEB  = 'web';
    const MESSAGE_TYPE_POST = 'post';

    /**
     * @var string Customer type values (Payolution)
     *
     * "P" for person, "C" for company.
     */
    const CUSTOMER_TYPE_PERSON  = 'P';
    const CUSTOMER_TYPE_COMPANY = 'C';

    /**
     * Response codes.
     * These are listed in some parts of the documentation as
     * strings with leading zeros, and in other parts as integers.
     * So there is some ambiguity about what we will receive, and
     * whether we can guarantee it can be cast to an integer for
     * comparison.
     * CHECKME: what is AUTHENTIFIED?
     */
    const RESPONSE_CODE_INCOMPLETE = '00';
    const RESPONSE_CODE_READY_FOR_SETTLEMENT = '01';
    const RESPONSE_CODE_DEBIT_WAITING_SETTLEMENT = '02';
    const RESPONSE_CODE_CREDIT_WAITING_SETTLEMENT = '03';
    const RESPONSE_CODE_DECLINED = '04';
    const RESPONSE_CODE_REFERRAL_STATUS = '05';
    const RESPONSE_CODE_CANCELLED_MERCHANT_1 = '06';
    const RESPONSE_CODE_CANCELLED_MERCHANT_2 = '07';
    const RESPONSE_CODE_CANCELLED_MERCHANT_3 = '08';
    const RESPONSE_CODE_CANCELLED_USER = '09';
    const RESPONSE_CODE_AUTHENTIFIED = '11';
    const RESPONSE_CODE_AUTHENTIFIED_AUTHORIZED = '12';
    const RESPONSE_CODE_PENDING_TRANSACTION = '13';
    const RESPONSE_CODE_TIMEOUT = '14';
    const RESPONSE_CODE_CARD_CHECK_PROCESSED = '15';
    const RESPONSE_CODE_RECORD_NOT_FOUND = '20';
    const RESPONSE_CODE_ALREADY_SETTLED = '21';
    const RESPONSE_CODE_MULTIPLE_ONE_TRANSACTION = '30';
}

<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Datatrans\Gateway;

class XmlPurchaseRequest extends XmlAuthorizationRequest
{
    /**
     * Inidates authorize and settle.
     */
    protected $requestType = Gateway::REQTYPE_PURCHASE;
}

<?php

namespace Omnipay\Datatrans\Message;

/**
 * Datatrans Notification Request.
 */

use Omnipay\Datatrans\Traits\HasCompleteResponse;
use Omnipay\Datatrans\Traits\HasGatewayParameters;
use Omnipay\Datatrans\Traits\VerifiesSignatures;
use Omnipay\Datatrans\Helper;

class AcceptNotification extends AbstractNotification
{
    use HasGatewayParameters, HasCompleteResponse {
        HasCompleteResponse::getVirtualCardno insteadof HasGatewayParameters;
    }
    use VerifiesSignatures;

    /**
     * @var array the data sent from the gateway, parsed into a flat array.
     */
    protected $data;

    /**
     * @return array
     */
    public function getData()
    {
        // Cache the data the first time we fetch it, as parsing the XML
        // data can be intensive, and is called many times.

        if ($this->data === null) {
            $this->data = Helper::extractMessageData($this->httpRequest);
        }

        return $this->data;
    }

    /**
     * Was the transaction successful?
     *
     * @return string Transaction status, one of {@see STATUS_COMPLETED}, {@see #STATUS_PENDING},
     * or {@see #STATUS_FAILED}.
     */
    public function getTransactionStatus()
    {
        if ($this->isSuccessful()) {
            return static::STATUS_COMPLETED;
        }

        // TODO: look out for static::STATUS_PENDING
        // Possibly a response code of 13 tells us this.
        // pendingPayPal also to be looked into.

        return static::STATUS_FAILED;
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
    public function getTransactionReference()
    {
        return $this->getDataItem('uppTransactionId', '');
    }

    /**
     * The notification is neither a request nor a response, but some
     * implementations will treat it like a request. Handle these by
     * returning self.
     */
    public function send()
    {
        $this->assertSignature();

        return $this;
    }
}

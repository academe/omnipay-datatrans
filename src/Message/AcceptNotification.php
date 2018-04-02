<?php

namespace Omnipay\Datatrans\Message;

/**
 * Datatrans Notification Request.
 */

//use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Datatrans\Traits\HasCompleteResponse;
//use Omnipay\Datatrans\Traits\HasRequestParameters;
use Omnipay\Datatrans\Traits\HasGatewayParameters;
use Omnipay\Datatrans\Traits\HasSignatureVerifier;

class AcceptNotification extends AbstractNotification
{
    use HasCompleteResponse;
    use HasGatewayParameters;
    use HasSignatureVerifier;

    /**
     * @return array
     *
     * TODO: move this to a common place, include XML support, then in this class cache the results.
     */
    public function getData()
    {
        // The results could be sent by GET or POST. It's an account
        // or request option.

        if (strtoupper($this->httpRequest->getMethod()) === 'POST') {
            return $this->httpRequest->request->all();
        } else {
            return $this->httpRequest->query->all();
        }
    }

    public function getHeaders()
    {
        // Header name is "upptransaction", with the XML url encoded into a string.
        // The XML, like when delivered in the body, is three levels deep,
        // and makes use of both attributes and content.
        // It can be flattended by:
        // a) ignoring all element names that contain other elements.
        // b) turn elements with no attributes and just data into a key/value pairs.
        // c) turn "parameter" elements with a "name" attribute into name/value pairs.
        // d) turn all other attributes and their values as name/value pairs.
        // There should not be any clashes, as all those names will be unique across
        // the whole XML file.
        // e.g.
        // <userParameters>otherelemtns</userParameters> -> ignore
        // <parameter name="maskedCC">424242xxxxxx4242</parameter> -> "maskedCC" => "424242xxxxxx4242"
        // <language>en</language> -> "language" => "en"
        // <transaction refno="trans274074862336" status="success"> -> "refno"=>"trans274074862336" and "status"=>"success"

        return urldecode($this->httpRequest->headers->get('upptransaction', null, true));
    }

    public function getBody()
    {
        return (string)$this->httpRequest->getContent();
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

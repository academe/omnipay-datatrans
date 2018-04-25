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

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Datatrans\Gateway;
use Omnipay\Datatrans\Helper;

/**
 * Datatrans abstract request.
 * Implements all property setters and getters.
 */
abstract class AbstractXmlRequest extends AbstractRequest
{
    /**
     * The XML API Endpoint Base URL
     *
     * @var string
     */
    protected $apiBaseProdUrl = 'https://api.datatrans.com/upp/jsp';

    /**
     * The XML API Endpoint Base URL
     *
     * @var string
     */
    protected $apiBaseTestUrl = 'https://api.sandbox.datatrans.com/upp/jsp';

    /**
     * defines the endpoint for a specific api
     *
     * @var string
     */
    protected $apiEndpoint;

    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var string (containing an int)
     */
    protected $serviceVersion = Gateway::XML_SERVICE_VERSION;

    abstract public function getData();

    /**
     * @param $requestElement
     * @return mixed
     */
    protected function prepareRequestXml($requestElement)
    {
        $fields = $this->getData();

        foreach ($fields as $key => $value) {
            // Supports multiple levels of nesting in the request element, thoough
            // there does not appear to be anywhere this is used.

            if (is_array($value)) {
                $array = $requestElement->addChild($key);

                foreach ($value as $subKey => $subValue) {
                    $array->addChild($subKey, $subValue);
                }
            } else {
                $requestElement->addChild($key, $value);
            }
        }

        return $requestElement;
    }

    /**
     * Generate XML for request
     *
     * @return \SimpleXMLElement
     */
    protected function getRequestXml()
    {
        $serviceXmlNode = "<" . $this->getServiceName() . "/>";

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . $serviceXmlNode);
        $xml->addAttribute('version', $this->getServiceVersion());

        $bodyChild = $xml->addChild('body');
        $bodyChild->addAttribute('merchantId', $this->getMerchantId());

        $transactionChild = $bodyChild->addChild('transaction');
        $transactionChild->addAttribute('refno', $this->getTransactionId());

        $requestChild = $transactionChild->addChild('request');

        $this->prepareRequestXml($requestChild);

        // Sign the request (last child of the "request" element.

        $requestChild->addChild('sign', $this->getSigning());

        return $xml;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @return int
     */
    public function getServiceVersion()
    {
        return $this->serviceVersion;
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * @param mixed $data
     * @return XmlResponse
     *
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            array(
                'Accept' => 'application/xml',
                'Content-type' => 'application/xml',
            ),
            $this->getRequestXml()->asXML()
        );

        try {
            // CURL_SSLVERSION_TLSv1_2 for libcurl < 7.35
            $httpRequest->getCurlOptions()->set(CURLOPT_SSLVERSION, 6);
            $httpResponse = $httpRequest->send();

            // Empty response body should be parsed also as an empty array
            $body = $httpResponse->getBody(true);

            $response = Helper::extractMessageData($httpResponse);
            return $this->response = $this->createResponse($response);

            //throw new InvalidResponseException('Error communicating with payment gateway');
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * @param $data
     *
     * @return XmlResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new XmlResponse($this, $data);
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        $base = $this->getTestMode() ? $this->getApiBaseTestUrl() : $this->getApiBaseProdUrl();
        return $base . '/' . $this->getApiEndpoint();
    }

    /**
     * @return string
     */
    public function getApiBaseProdUrl()
    {
        return $this->apiBaseProdUrl;
    }

    /**
     * @return string
     */
    public function getApiBaseTestUrl()
    {
        return $this->apiBaseTestUrl;
    }

    /**
     * @return string
     */
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }
}

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

use Omnipay\Datatrans\Traits\HasSignatureVerifier;
use SimpleXMLElement;
use Exception;

class CompleteRequest extends AbstractRequest
{
    use HasSignatureVerifier;

    /**
     * @return array
     */
    public function getData()
    {
        // The results could be sent by GET or POST. It's an account
        // option, or an overriding request option.
        // Could also be XML in a header or the body.

        if (strtoupper($this->httpRequest->getMethod()) === 'POST') {
            return $this->httpRequest->request->all();
        } else {
            return $this->httpRequest->query->all();
        }
    }

    /**
     * Parse a SimpleXML object to a flat array.
     * Start by passing in the parsed XML string: simplexml_load_string($xmlString)
     *
     * @return array
     */
    protected function parseXmlElement(SimpleXMLElement $element, $result = [])
    {
        $attributes = $element->attributes();

        if ($element->getName() === 'parameter' && $attributes->name) {
            $result[(string)$attributes->name] = (string)$xml;
        } else {
            if ($attributes) {
                foreach ($attributes as $name => $value) {
                    $result[$name] = (string)$value;
                }
            }

            if ($element->count()) {
                // Has children.
                foreach ($element as $childElement) {
                    $result = parseXmlElement($childElement, $result);
                }
            } else {
                // Is a leaf node.
                $result[$element->getName()] = (string)$element;
            }
        }

        return $result;
    }

    /**
     * Get a single data item, or return a default.
     */
    protected function getDataItem($name, $default = null)
    {
        if (array_key_exists($name, $this->getData())) {
            return $this->getData()[$name];
        }

        return $default;
    }

    /**
     * @return ResponseInterface
     */
    public function send()
    {
        if ($key = $this->getHmacKey()) {
            $this->assertSignature();
        }

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
        return $this->response = new CompleteResponse($this, $data);
    }
}

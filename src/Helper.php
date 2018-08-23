<?php

namespace Omnipay\Datatrans;

/**
 *
 */

use Symfony\Component\HttpFoundation\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleXMLElement;

class Helper
{
    /**
     * Return the data supplied in a server request.
     * The data may be in GET parameter, POST paramsters,
     * as XML in a header line or XML in the request body.
     * All of these methods are used in various places.
     * XML data is parsed into a flat array.
     *
     * @param Request|ResponseInterface $httpMessage a HTTP server request or a PSR-7 message
     * @return array the data as a flat array
     */
    public static function extractMessageData($httpMessage)
    {
        // The assumption for now is that a syncronous response will always be XML.

        if ($httpMessage instanceof ResponseInterface) {
            $xmlString = (string)$httpMessage->getBody();

            $xmlString = simplexml_load_string($xmlString);
            return static::parseXmlElement($xmlString);
        }

        // Incoming server request.
        // The results could be sent by GET or POST. It's an account
        // option, or an overriding request option.
        // Could also be XML in a header field or the body.

        if ($httpMessage instanceof Request) {
            if (static::getMethod($httpMessage) === 'POST') {
                // Check if XML data is in the body.

                if ($httpMessage->headers->get('Content-Type', '') === 'text/xml') {
                    $xmlString = (string)$httpMessage->getContent();

                    if ($xmlString) {
                        $xmlString = simplexml_load_string($xmlString);
                        return static::parseXmlElement($xmlString);
                    }
                }
            } else {
                // Check if XML data is in the header.

                $xmlString = static::extractXmlHeader($httpMessage);

                if ($xmlString) {
                    $xmlString = simplexml_load_string($xmlString);
                    return static::parseXmlElement($xmlString);
                }
            }

            // Fall back to standard GET query or POST form parameters.

            if (static::getMethod($httpMessage) === 'POST') {
                return $httpMessage->request->all();
            } else {
                return $httpMessage->query->all();
            }
        }

        return [];
    }

    public static function getMethod($httpMessage)
    {
        return strtoupper($httpMessage->getMethod());
    }

    /**
     * Parse a SimpleXML object to a flat array.
     * Start by passing in the parsed XML string: simplexml_load_string($xmlString)
     *
     * There should not be any clashes, as all those names will be unique across
     * the whole XML file. This is a feature of the data supplied by this gateway.
     *
     * e.g.
     * <userParameters>otherelemtns</userParameters> -> ignore
     * <parameter name="maskedCC">424242xxxxxx4242</parameter> -> "maskedCC" => "424242xxxxxx4242"
     * <language>en</language> -> "language" => "en"
     * <transaction refno="trans274074862336" status="success"> -> "refno"=>"trans274074862336" and "status"=>"success"
     *
     * Parsing used these rules to flatten the XML:
     *
     * a) ignoring all element names that contain other elements.
     * b) turn elements with no attributes and just data into a key/value pairs.
     * c) turn "parameter" elements with a "name" attribute into name/value pairs.
     * d) turn all other attributes and their values as name/value pairs.
     *
     * @param SimpleXMLElement $element
     * @param array $result the resulting flat array that is built up
     * @return array
     */
    protected static function parseXmlElement(SimpleXMLElement $element, $result = [])
    {
        $attributes = $element->attributes();

        if ($element->getName() === 'parameter' && $attributes->name) {
            $result[(string)$attributes->name] = (string)$element;
        } else {
            if ($attributes) {
                foreach ($attributes as $name => $value) {
                    $result[$name] = (string)$value;
                }
            }

            if ($element->count()) {
                // Has children; recurse to parse them.
                foreach ($element as $childElement) {
                    $result = static::parseXmlElement($childElement, $result);
                }
            } else {
                // Is a leaf node.
                $result[$element->getName()] = (string)$element;
            }
        }

        return $result;
    }

    /**
     * Extract the XML string from the header, if present.
     */
    public static function extractXmlHeader($httpMessage)
    {
        // Header name is "upptransaction", with the XML url encoded into a string.
        // The XML, like when delivered in the body, is three levels deep,
        // and makes use of both attributes and content.

        return urldecode($httpMessage->headers->get('upptransaction', null, true));
    }
}

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

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Datatrans XML Response
 */
class XmlResponse extends AbstractResponse
{
    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl()
    {
        return null;
    }

    /**
     * Get the required redirect method (either GET or POST).
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     *
     * @return []
     */
    public function getRedirectData()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return empty($this->data['error']);
    }

    /**
     * @return string|null
     */
    public function getTransactionReference()
    {
        // This is usually correct for payments, authorizations, etc
        if (!empty($this->data['transactions']) && !empty($this->data['transactions'][0]['related_resources'])) {
            foreach (array('sale', 'authorization') as $type) {
                if (!empty($this->data['transactions'][0]['related_resources'][0][$type])) {
                    return $this->data['transactions'][0]['related_resources'][0][$type]['id'];
                }
            }
        }

        // This is a fallback, but is correct for fetch transaction and possibly others
        if (!empty($this->data['id'])) {
            return $this->data['id'];
        }

        return null;
    }

    /**
     * @return null
     */
    public function getMessage()
    {
        if (isset($this->data['error'])) {
            return $this->data['error']['errorDetail'];
        }

        if (isset($this->data['response'])) {
            return $this->data['response']['responseMessage'];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        if (isset($this->data['response'])) {
            return $this->data['response']['responseCode'];
        }

        if (isset($this->data['error'])) {
            return $this->data['error']['errorCode'];
        }

        return '9999';
    }
}

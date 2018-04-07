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
use Omnipay\Datatrans\Helper;
use Exception;

class CompleteRequest extends AbstractRequest
{
    use HasSignatureVerifier;

    /**
     * @return array
     */
    public function getData()
    {
        return Helper::getRemoteData($this->httpRequest);
    }

    /**
     * FIXME: do we need this here? It does not appear to be used.
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

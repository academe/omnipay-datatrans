<?php
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

namespace Omnipay\Datatrans\Message;

/**
 * Datatrans Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        $status = $this->getStatus();

        return $status === 'success';
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return false;
    }

    protected function getDataItem($name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $default;
    }

    public function getNumberLastFour()
    {
        return substr($this->getDataItem('maskedCC'), -4, 4) ?: null;
    }

    /**
     * Return an Omnipay format mask by default.
     * Set $mast to null to return the raw gateway masked card number.
     */
    public function getNumberMasked($mask = 'X')
    {
        $cardNumber = $this->getDataItem('maskedCC');

        if ($mask === null) {
            return $cardNumber;
        }

        $maskLength = strlen($cardNumber) - 4;
        return str_repeat($mask, $maskLength) . $this->getNumberLastFour();
    }

    // TODO etc for month/year/date too
}

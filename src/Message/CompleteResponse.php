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
class CompleteResponse extends AbstractResponse
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

    /**
     * Get the last 4 digits of the card number.
     *
     * @return string
     */
    public function getNumberLastFour()
    {
        return substr($this->getDataItem('maskedCC'), -4, 4) ?: null;
    }

    /**
     * Returns a masked credit card number with only the last 4 chars visible
     * Return an Omnipay format mask by default.
     * Set $mast to null to return the raw gateway masked card number.
     *
     * @param string $mask Character to use in place of numbers
     * @return string
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

    /**
     * Get the card expiry month.
     *
     * @return int
     */
    public function getExpiryMonth()
    {
        return intval($this->getDataItem('expm'));
    }

    /**
     * Get the card expiry year.
     *
     * @return int
     */
    public function getExpiryYear()
    {
        return intval($this->getDataItem('expy'));
    }

    /**
     * Get the card expiry date, using the specified date format string.
     *
     * @param string $format
     * @return string
     */
    public function getExpiryDate($format)
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->getExpiryMonth(), 1, $this->getExpiryYear()));
    }}

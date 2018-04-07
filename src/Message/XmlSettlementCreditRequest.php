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

/**
 * Class XmlSettlementCreditRequest
 *
 * @package Omnipay\Datatrans\Message
 */

use Omnipay\Datatrans\Gateway;

class XmlSettlementCreditRequest extends XmlSettlementRequest
{
    /**
     * @return string
     */
    public function getTransactionType()
    {
        return Gateway::TRANSACTION_TYPE_CREDIT;
    }
}

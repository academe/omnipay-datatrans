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

use Omnipay\Datatrans\AbstractDatatransGateway;

class PurchaseRequest extends AbstractRedirectRequest
{
    /**
     * @var string NOA or CAA (null to use account default)
     */
    protected $requestType = AbstractDatatransGateway::REQTYPE_PURCHASE;
}

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

use Omnipay\Datatrans\Traits\HasCompleteResponse;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Datatrans\Gateway;


/**
 * Datatrans Complete Purchase/Authorize Response
 */
class CompleteResponse extends AbstractResponse
{
    use HasCompleteResponse;
}

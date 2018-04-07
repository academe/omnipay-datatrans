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

/**
 * Datatrans XML Response
 */
class XmlResponse extends AbstractResponse
{
    use HasCompleteResponse;

    /**
     * The XML responses have a slightly different way to check for success.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getErrorCode() === null;
    }
}

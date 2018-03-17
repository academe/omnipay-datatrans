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

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Datatrans purchase redirect response
 */
abstract class AbstractResponse extends OmnipayAbstractResponse implements RedirectResponseInterface
{
    /**
     * @var string
     */
    protected $productionEndpoint = 'https://pay.datatrans.com/upp/jsp/upStart.jsp';

    /**
     * @var string
     */
    protected $testEndpoint = 'https://pay.sandbox.datatrans.com/upp/jsp/upStart.jsp';
    protected $testEndpointIso = 'https://pay.sandbox.datatrans.com/upp/jsp/upStartIso.jsp';

    /*** STATUS CODES ****/
    /**
     * success code in response
     */
    const DATATRANS_SUCCESS = '01';

    /**
     * error code in response
     */
    const DATATRANS_ERROR = '02';

    /**** ALIAS ERRORS ****/

    /**
     * CC alias update error
     */
    const DATATRANS_ALIAS_UPDATE_ERROR = '-885';

    /**
     * CC alias insert error
     */
    const DATATRANS_ALIAS_INSERT_ERROR = '-886';

    /**
     * CC alias does not match with cardno
     */
    const DATATRANS_ALIAS_CARD_NO = '-887';

    /**
     * CC alias not found
     */
    const DATATRANS_ALIAS_NOT_FOUND = '-888';

    /**
     * CC alias error / input parameters missing
     */
    const DATATRANS_ALIAS_ERROR = '-889';

    /**
     * CC alias service is not supported
     */
    const DATATRANS_ALIAS_SERVICE_NOT_SUPPORTED = '-900';

    /**
     * generel error
     */
    const DATATRANS_ALIAS_GENEREL_ERROR = '-999';

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        return $this->getCheckoutEndpoint();
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        // Build the post data as expected by Datatrans.
        $params = $this->getData();
        $getData = array();
        foreach ($params as $key => $value) {
            $getData[$key] = $value;
        }

        return $getData;
    }

    /**
     * @return string
     */
    protected function getCheckoutEndpoint()
    {
        $req = $this->getRequest();

        if ($req->getTestMode()) {
            return $this->testEndpoint;
        }

        return $this->productionEndpoint;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return $this->data['responseMessage'];
        }

        return '';
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return isset($this->data['refno']) ? $this->data['refno'] : '';
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->data['status'];
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->data['code'];
    }
}

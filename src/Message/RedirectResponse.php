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

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Datatrans purchase redirect response.
 * Handles [local] redirection responses for authorize, purchase, createCard.
 */
class RedirectResponse extends AbstractResponse implements RedirectResponseInterface
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

    /**
     * success code in response
     */
    const DATATRANS_SUCCESS = '01';

    /**
     * error code in response
     */
    const DATATRANS_ERROR = '02';

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
}

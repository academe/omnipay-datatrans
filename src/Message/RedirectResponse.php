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
        if ($this->getRedirectMethod() === 'POST') {
            return $this->getCheckoutEndpoint();
        } else {
            // Add the redierect data onto the endpoint URL.

            $parts = parse_url($this->getCheckoutEndpoint());
            if (array_key_exists('query', $parts)) {
                parse_str($parts['query'], $query);
                $query = array_merge($query, $this->getRedirectData());
            } else {
                $query = $this->getRedirectData();
            }

            return sprintf(
                '%s://%s%s?%s',
                $parts['scheme'],
                $parts['host'],
                $parts['path'],
                http_build_query($query)
            );
        }
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        $data = $this->getData();

        if (array_key_exists('redirectMethod', $data) && strtoupper($data['redirectMethod']) === 'GET') {
            return 'GET';
        }

        return 'POST';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        if ($this->getRedirectMethod() === 'POST') {
            return array_diff_key($this->getData(), ['redirectMethod' => null]);
        } else {
            return [];
        }
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

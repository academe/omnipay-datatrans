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
        return array_diff_key($this->getData(), ['redirectMethod' => null]);
    }

    /**
     * Converts the data so it is suitable for use in lightbox mode.
     * All data keys are converted to the data-* camel-case format.
     * @return array
     */
    public function getLightboxData()
    {
        $data = $this->getData();

        $newKeys = array_map([$this, 'toLightboxFormat'], array_keys($data));

        return array_combine($newKeys, array_values($data));
    }

    /**
     * Returns a HTML attributes string for lightbox mode.
     * All values are HTML encoded, so it can be used without further encoding
     * in a HTML template.
     */
    public function getLightboxHtmlAttributes($quote = '"')
    {
        $attributes = [];

        foreach ($this->getLightboxData() as $key => $value) {
            $attributes[] = $key . '=' . $quote . htmlspecialchars($value) . $quote;
        }

        return implode(' ', $attributes);
    }

    /**
     * Convert string from camel case parameter name to lightbox-mode name.
     */
    protected function toLightboxFormat($camelCase) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $camelCase, $matches);
        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return 'data-' . implode('-', $ret);
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

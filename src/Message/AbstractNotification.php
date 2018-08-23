<?php

namespace Omnipay\Datatrans\Message;

/**
 * Datatrans Notification Request Abstaract.
 * The Omnipay documentation states that the AcceptNotification does
 * not accept parameters, and there is subsequently not an abstract
 * notification in the Omnipay Common library to support parameters.
 * This abstract meets that need, for now.
 */

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Datatrans\Traits\HasGatewayParameters;
use Omnipay\Datatrans\Traits\HasSignatureVerifier;

use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

abstract class AbstractNotification implements NotificationInterface
{
    /**
     * The request parameters
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * The request client.
     *
     * @var \Omnipay\Common\Http\ClientInterface
     */
    protected $httpClient;

    /**
     * The HTTP request object.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient  A client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
        $this->initialize();
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     */
    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * Get all parameters as an associative array.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * Get a single parameter.
     *
     * @param string $key The parameter key
     * @return mixed
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Set a single parameter
     *
     * @param string $key The parameter key
     * @param mixed $value The value to set
     * @return AbstractRequest Provides a fluent interface
     * @throws RuntimeException if a request parameter is modified after the request has been sent.
     */
    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }
}

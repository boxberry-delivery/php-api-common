<?php

namespace Boxberry\Common;

use Boxberry\Common\Http\Client;
use Boxberry\Common\Message\Request\AbstractRequest;
use Boxberry\Common\Message\Response\AbstractResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Базовый класс достаки
 *
 * @package Boxberry\Common
 */
abstract class AbstractDelivery implements DeliveryInterface
{
    /**
     * Параметры достаки
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * PSR-7 Http клиент
     *
     * @var Client
     */
    protected $httpClient;

    /**
     * Объект запроса
     *
     * @var HttpRequest
     */
    protected $httpRequest;

    /**
     * Создает объект доставки
     *
     * @param Client|null      $httpClient
     * @param HttpRequest|null $httpRequest
     */
    public function __construct(Client $httpClient = null, HttpRequest $httpRequest = null)
    {
        $this->httpClient = $httpClient ?: $this->getDefaultHttpClient();
        $this->httpRequest = $httpRequest ?: $this->getDefaultHttpRequest();
        $this->initialize();
    }

    /**
     * @return Client
     */
    protected function getDefaultHttpClient()
    {
        return new Client();
    }

    /**
     * @return HttpRequest
     */
    protected function getDefaultHttpRequest()
    {
        return HttpRequest::createFromGlobals();
    }

    /**
     * @inheritdoc
     */
    public function initialize(array $parameters = [])
    {
        $this->parameters = new ParameterBag;
        // set default parameters
        foreach ($this->getDefaultParameters() as $key => $value) {
            if (is_array($value)) {
                $this->parameters->set($key, reset($value));
            } else {
                $this->parameters->set($key, $value);
            }
        }
        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultParameters()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getShortName()
    {
        return Helper::getDeliveryShortName(get_class($this));
    }

    /**
     * @param  string $key
     *
     * @return mixed
     */
    public function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     *
     * @return $this
     */
    public function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * @see AbstractResponse
     *
     * @param string $class
     * @param array  $parameters
     *
     * @return AbstractRequest
     */
    protected function createRequest($class, array $parameters)
    {
        /** @var AbstractRequest $obj */
        $obj = new $class($this->httpClient, $this->httpRequest);

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }
}

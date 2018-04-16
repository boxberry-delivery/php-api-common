<?php


namespace Boxberry\Common;

use Boxberry\Common\Exception\RuntimeException;
use Boxberry\Common\Http\Client;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class DeliveryFactory
{
    /**
     * @var array
     */
    private $deliveries = [];

    /**
     * Создает новый объект доставки
     *
     * @param string           $class
     * @param Client|null      $httpClient
     * @param HttpRequest|null $httpRequest
     *
     * @return DeliveryInterface
     * @throws RuntimeException
     */
    public function create($class, Client $httpClient = null, HttpRequest $httpRequest = null)
    {
        $class = Helper::getDeliveryClassName($class);
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return new $class($httpClient, $httpRequest);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->deliveries;
    }

    /**
     * @param array $deliveries
     */
    public function replace(array $deliveries)
    {
        $this->deliveries = $deliveries;
    }

    /**
     * @param $className
     */
    public function register($className)
    {
        if (!in_array($className, $this->deliveries)) {
            $this->deliveries[] = $className;
        }
    }
}

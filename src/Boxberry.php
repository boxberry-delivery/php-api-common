<?php

namespace Boxberry\Common;

use Boxberry\Common\Http\Client;

/**
 * Class Boxberry
 *
 * @codingStandardsIgnoreStart
 * @method static DeliveryInterface create(string $class, Client $httpClient = null,
 *         \Symfony\Component\HttpFoundation\Request $httpRequest = null)
 * @codingStandardsIgnoreEnd
 * @package Boxberry\Common
 */
class Boxberry
{
    /**
     * @var DeliveryFactory
     */
    private static $factory;

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $factory = self::getFactory();

        return call_user_func_array([$factory, $method], $parameters);
    }

    /**
     * @return DeliveryFactory
     */
    public static function getFactory()
    {
        if (self::$factory === null) {
            self::$factory = new DeliveryFactory;
        }

        return self::$factory;
    }

    /**
     * @param DeliveryFactory|null $factory
     */
    public static function setFactory(DeliveryFactory $factory = null)
    {
        self::$factory = $factory;
    }
}

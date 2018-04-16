<?php


namespace Boxberry\Common;

interface DeliveryInterface
{
    /**
     * Возвращает имя используемого сервиса
     *      • b2c - для интернет магазинов;
     *      • с2c - ПиП (письма и посылки);
     *
     * @return string
     */
    public function getName();

    /**
     * Возвращает короткое имя класса используемого сервиса
     *
     * @see DeliveryFactory
     * @return string
     */
    public function getShortName();

    /**
     * Возвращает параметры по умолчанию для сервиса в формате:
     * array(
     *     'token' => '',
     *     'endPoint' => '',
     * );
     *
     * @return array
     */
    public function getDefaultParameters();

    /**
     * Заполняет сервис переданными параметрами
     *
     * $parameters = [
     *      'api_token' string  (Required)  Ключ доступа
     *      'endPoint'  string  (Required)  Урл api
     * ]
     *
     * @param array $parameters Входные параметры (см. выше)
     *
     * @return $this
     */
    public function initialize(array $parameters = []);

    /**
     * Возвращает все параметры сервиса
     *
     * @return array
     */
    public function getParameters();
}

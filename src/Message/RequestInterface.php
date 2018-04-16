<?php


namespace Boxberry\Common\Message;

/**
 * Интерфейс запроса
 *
 * @package Boxberry\Common\Message
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Инициализация параметров запроса
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function initialize(array $parameters = []);

    /**
     * Возвращает все параметры запроса
     *
     * @return array
     */
    public function getParameters();

    /**
     * Возвращает полученный ответ
     *
     * @return ResponseInterface
     */
    public function getResponse();

    /**
     * Отправка запроса
     *
     * @return ResponseInterface
     */
    public function send();

    /**
     * Реальная отправка данных
     *
     * @internal
     *
     * @param mixed $data
     *
     * @return ResponseInterface|object
     */
    public function sendData($data);
}

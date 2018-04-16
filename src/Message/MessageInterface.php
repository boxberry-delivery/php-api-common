<?php


namespace Boxberry\Common\Message;

/**
 * Интерфейс сообщения запроса-ответа
 *
 * @package Boxberry\Common\Message
 */
interface MessageInterface
{
    /**
     * @return mixed
     */
    public function getData();
}

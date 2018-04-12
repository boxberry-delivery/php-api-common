<?php


namespace Dumkaaa\Boxberry\Api\Common\Message;

/**
 * Интерфейс сообщения запроса-ответа
 *
 * @package Dumkaaa\Boxberry\Api\Common\Message
 */
interface MessageInterface
{
    /**
     * @return mixed
     */
    public function getData();
}

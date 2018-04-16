<?php


namespace Boxberry\Common\Message;

use Boxberry\Common\Model\CreateFromAttributesInterface;

/**
 * Интерфейс ответа
 *
 * @package Boxberry\Common\Message
 */
interface ResponseInterface extends MessageInterface
{

    /**
     * Возвращает объект запроса
     *
     * @return RequestInterface
     */
    public function getRequest();

    /**
     * Возвращает ошибки
     *
     * @return null|string
     */
    public function getError();

    /**
     * Возвращает статус
     *
     * @return null|string
     */
    public function getStatus();

    /**
     * Запрос успешен?
     *
     * @return bool
     */
    public function isSuccessful();

    /**
     * Возвращает данные, преобразуя их в коллекцию объектов
     *
     * @param string $collectionClass класс для наполнения им коллекции
     *
     * @return object[]
     */
    public function getCollection($collectionClass = CreateFromAttributesInterface::class);
}

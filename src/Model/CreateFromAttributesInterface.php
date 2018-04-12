<?php


namespace Dumkaaa\Boxberry\Api\Common\Model;

interface CreateFromAttributesInterface
{
    /**
     * Создает модель из переданных атрибутов
     *
     * @param array $attributes
     *
     * @return object|AbstractModel
     */
    public static function createFromAttributes(array $attributes);
}

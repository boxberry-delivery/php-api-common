<?php


namespace Boxberry\Common\Model;

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

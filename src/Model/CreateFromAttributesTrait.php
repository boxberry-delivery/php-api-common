<?php


namespace Boxberry\Common\Model;

trait CreateFromAttributesTrait
{
    /**
     * @inheritdoc
     */
    public static function createFromAttributes(array $attributes)
    {
        $class = static::class;

        return new $class($attributes);
    }
}

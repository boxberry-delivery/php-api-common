<?php


namespace Dumkaaa\Boxberry\Api\Common\Model;

/**
 * Абстрактная модель
 *
 * @package Dumkaaa\Boxberry\Api\Common\Model
 */
abstract class AbstractModel implements CreateFromAttributesInterface
{
    /**
     * AbstractModel constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $propertyName => $propertyValue) {
            $methodName = 'set' . $propertyName;
            if (method_exists($this, $methodName)) {
                $this->$methodName($propertyValue);
            } elseif (property_exists($this, $propertyName)) {
                $this->$propertyName = $propertyValue;
            }
        }
    }
}

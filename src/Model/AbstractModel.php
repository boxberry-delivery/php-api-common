<?php


namespace Boxberry\Common\Model;

/**
 * Абстрактная модель
 *
 * @package Boxberry\Common\Model
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

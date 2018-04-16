<?php


namespace Boxberry\Common;

class Helper
{
    /**
     * Инициализация парамметров
     *
     * @param            $target
     * @param array|null $parameters
     */
    public static function initialize($target, array $parameters = null)
    {
        if ($parameters) {
            foreach ($parameters as $key => $value) {
                $method = 'set' . ucfirst(static::camelCase($key));
                if (method_exists($target, $method)) {
                    $target->$method($value);
                }
            }
        }
    }

    /**
     * @param $str
     *
     * @internal
     * @return null|string|string[]
     */
    protected static function camelCase($str)
    {
        $str = self::convertToLowercase($str);

        return preg_replace_callback(
            '/_([a-z])/',
            function ($match) {
                return strtoupper($match[1]);
            },
            $str
        );
    }

    /**
     * @internal
     *
     * @param $str
     *
     * @return string
     */
    protected static function convertToLowercase($str)
    {
        $explodedStr = explode('_', $str);
        $lowercasedStr = [];
        if (count($explodedStr) > 1) {
            foreach ($explodedStr as $value) {
                $lowercasedStr[] = strtolower($value);
            }
            $str = implode('_', $lowercasedStr);
        }

        return $str;
    }

    /**
     * @internal
     *
     * @param $array
     *
     * @return array
     */
    public static function filterEmpty($array)
    {
        return array_filter(
            $array,
            function ($val, $key) {
                return $key && $val;
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @internal
     *
     * @param $className
     *
     * @return string
     */
    public static function getDeliveryShortName($className)
    {
        if (0 === strpos($className, '\\')) {
            $className = substr($className, 1);
        }
        if (0 === strpos($className, 'Boxberry\\')) {
            return trim(str_replace('\\', '_', substr($className, 8, -7)), '_');
        }

        return '\\' . $className . '\\Delivery';
    }

    /**
     * @param  string $shortName
     *
     * @return string
     */
    public static function getDeliveryClassName($shortName)
    {
        if (0 === strpos($shortName, '\\')) {
            return $shortName;
        }

        $shortName = str_replace('_', '\\', $shortName);
        if (false === strpos($shortName, '\\')) {
            $shortName .= '\\';
        }

        return '\\Boxberry\\' . $shortName . 'Delivery';
    }
}

<?php


namespace Dumkaaa\Boxberry\Api\Common\Exception;

/**
 * Class InvalidResponseException
 *
 * @package Dumkaaa\Boxberry\Api\Common\Exception
 */
class InvalidResponseException extends \Exception implements BoxberryException
{
    public function __construct($message = 'Invalid response from delivery service', $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

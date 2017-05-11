<?php

namespace ArjanWestdorp\Imaginary\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    /**
     * Url not defined.
     *
     * @return static
     */
    public static function urlNotDefined()
    {
        return new static('Url is not defined in the config.');
    }

    /**
     * Client not defined.
     *
     * @return static
     */
    public static function clientNotDefined()
    {
        return new static('Client is not defined in the config.');
    }
}
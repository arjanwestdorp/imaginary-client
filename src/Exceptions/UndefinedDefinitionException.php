<?php

namespace ArjanWestdorp\Imaginary\Exceptions;

use Exception;

class UndefinedDefinitionException extends Exception
{
    /**
     * Custom definition not defined.
     *
     * @param string $definition
     * @return static
     */
    public static function definitionNotDefined($definition)
    {
        return new static('Definition: "' . $definition . '" not defined');
    }
}
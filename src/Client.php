<?php

namespace ArjanWestdorp\Imaginary;

use ArjanWestdorp\Imaginary\Exceptions\InvalidConfigurationException;
use ArjanWestdorp\Imaginary\Exceptions\UndefinedDefinitionException;
use Closure;

class Client
{
    /**
     * @var Builder
     */
    protected $builder;
    /**
     * @var array;
     */
    protected $definitions = [];
    /**
     * @var array
     */
    private $config;

    /**
     * Client constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Check if we can call a method on the manipulations builder.
     * Else check if a predefined manipulation set need to be called.
     *
     * @param string $method
     * @param array $arguments
     * @return $this
     * @throws UndefinedDefinitionException
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->builder, $method)) {
            call_user_func_array([$this->builder, $method], $arguments);

            return $this;
        } elseif (isset($this->definitions[$method]) && is_callable($this->definitions[$method])) {
            array_unshift($arguments, $this->builder);

            call_user_func_array($this->definitions[$method], $arguments);

            return $this;
        }

        throw UndefinedDefinitionException::definitionNotDefined($method);
    }

    /**
     * Define a predefined set of manipulations.
     *
     * @param string $key
     * @param Closure $callback
     * @return $this
     */
    public function define($key, Closure $callback)
    {
        $this->definitions[$key] = $callback;

        return $this;
    }

    /**
     * Let the client fetch the given url as image.
     *
     * @param string $url
     * @return $this
     */
    public function fetch($url)
    {
        $this->builder = new Builder($url);

        return $this;
    }

    /**
     * Retrieve the imaginary url.
     *
     * @return string
     * @throws InvalidConfigurationException
     */
    public function url()
    {
        if (!isset($this->config['url'])) {
            throw InvalidConfigurationException::urlNotDefined();
        }

        if (!isset($this->config['client'])) {
            throw InvalidConfigurationException::clientNotDefined();
        }

        return implode('/', array_filter([
            $this->config['url'],
            $this->config['client'],
            $this->getResourceKey(),
            $this->getTypeKey(),
            $this->builder->getManipulations(),
            $this->builder->getKey(),
        ]));
    }

    /**
     * Get the resource key.
     * We currently only support image.
     *
     * @return string
     */
    protected function getResourceKey()
    {
        return 'images';
    }

    /**
     * Get the type key.
     * We only support fetch at the moment.
     *
     * @return string
     */
    protected function getTypeKey()
    {
        return 'fetch';
    }

    /**
     * Return the url for this image.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->url();
    }
}
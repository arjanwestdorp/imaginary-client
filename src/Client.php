<?php

namespace ArjanWestdorp\Imaginary;

use ArjanWestdorp\Imaginary\Exceptions\InvalidConfigException;
use ArjanWestdorp\Imaginary\Exceptions\UndefinedDefinitionException;
use Closure;

class Client
{
    /**
     * @var array;
     */
    protected $definitions = [];
    /**
     * @var string
     */
    protected $key;
    /**
     * @var array
     */
    protected $manipulations;
    /**
     * @var string
     */
    protected $url;
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
     * Check if a predefined manipulation set need to be called.
     *
     * @param string $method
     * @param array $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if (isset($this->definitions[$method]) && is_callable($this->definitions[$method])) {

            array_unshift($arguments, $this);

            call_user_func_array($this->definitions[$method], $arguments);

            return $this;
        }

        throw UndefinedDefinitionException::definitionNotDefined($method);
    }

    /**
     * Make a circle of the image with the given radius.
     *
     * @param string $radius
     * @return $this
     */
    public function circle($radius = 'max')
    {
        $this->manipulations['r'] = $radius;

        return $this;
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
        // todo: url validation?
        $this->key = $url;

        return $this;
    }

    /**
     * Fit the image when resizing it.
     *
     * @param null|string $gravity
     * @return $this
     */
    public function fit($gravity = null)
    {
        $this->manipulations['c'] = 'fit';

        if (!is_null($gravity)) {
            $this->manipulations['g'] = $gravity;
        }

        return $this;
    }

    /**
     * Set the desired height of the image.
     *
     * @param int $height
     * @return $this
     */
    public function height($height)
    {
        $this->manipulations['h'] = $height;

        return $this;
    }

    /**
     * Retrieve the imaginary url.
     *
     * @return string
     * @throws InvalidConfigException
     */
    public function url()
    {
        if (!isset($this->config['url'])) {
            throw InvalidConfigException::urlNotDefined();
        }

        if (!isset($this->config['client'])) {
            throw InvalidConfigException::clientNotDefined();
        }

        return implode('/', array_filter([
            $this->config['url'],
            $this->config['client'],
            $this->getResourceKey(),
            $this->getTypeKey(),
            $this->getManipulations(),
            $this->key
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
        return 'image';
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
     * Get the string representation of all manipulations that
     * need to be executed on the given resource.
     *
     * @return string|null
     */
    public function getManipulations()
    {
        $manipulations = array_map(function ($key) {
            return $key . '_' . $this->manipulations[$key];
        }, array_keys($this->manipulations ?: []));

        return implode(',', $manipulations);
    }

    /**
     * Set the desired width of the image.
     *
     * @param int $width
     * @return $this
     */
    public function width($width)
    {
        $this->manipulations['w'] = $width;

        return $this;
    }
}
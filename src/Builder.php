<?php

namespace ArjanWestdorp\Imaginary;

class Builder
{
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
     * Builder constructor.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
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
     * Get the key where we build the url for.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}
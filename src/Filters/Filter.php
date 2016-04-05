<?php

namespace Mchekin\MVCBoiler\Filters;

abstract class Filter
{
    protected $message = 'Invalid value';
    protected $min = 3;
    protected $max = 255;

    /**
     * @param string $value
     * @return bool
     */
    abstract public function validate($value);

    /**
     * @param string $value
     * @return string
     */
    abstract public function sanitize($value);

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}

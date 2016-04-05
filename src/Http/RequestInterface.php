<?php

namespace Mchekin\MVCBoiler\Http;

interface RequestInterface
{

    /**
     * @param $key
     * @param null $default
     * @return string
     */
    public function get($key, $default = null);

    /**
     * @param $key
     * @param null $default
     * @return string
     */
    public function post($key, $default = null);

    /**
     * @param $key
     * @param null $default
     * @return string
     */
    public function server($key, $default = null);

    /**
     * @param $key
     * @param null $default
     * @return string
     */
    public function session($key, $default = null);

    /**
     * @return string
     */
    public function method();

    /**
     * @return array
     */
    public function payload();
}

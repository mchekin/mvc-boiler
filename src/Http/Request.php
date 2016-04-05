<?php

namespace Mchekin\MVCBoiler\Http;

class Request implements RequestInterface
{
    /**
     * @var array
     */
    protected $get = [];

    /**
     * @var array
     */
    protected $post = [];

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var array
     */
    protected $server = [];

    /**
     * @var array
     */
    protected $cookies = [];

    /**
     * @var array
     */
    protected $session;


    public function __construct()
    {
        if( !isset( $_SESSION ) ) {
            session_start();
        }

        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->cookies = $_COOKIE;
        $this->session = $_SESSION;
    }

    /**
     * @param $key
     * @param null $default
     * @return string
     */
    public function get($key, $default = null)
    {
        return $this->getKeyValue($this->get, $key, $default);
    }

    /**
     * @param $key
     * @param null $default
     * @return string
     */
    public function post($key, $default = null)
    {
        return $this->getKeyValue($this->post, $key, $default);
    }

    /**
     * @param $key
     * @param null $default
     * @return string
     */
    public function session($key, $default = null)
    {
        if ( isset($this->session[$key]) ) {
            $value = $this->session[$key];
            unset($_SESSION[$key]);
            return $value;
        }

        return $default;
    }

    /**
     * @param $key
     * @param null $default
     * @return string
     */
    public function server($key, $default = null)
    {
        return $this->getKeyValue($this->server, $key, $default);
    }

    /**
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD', 'GET');
    }

    /**
     * @return array
     */
    public function payload()
    {
        $rawPayload   = file_get_contents('php://input');
        $contentType  = $this->server('CONTENT_TYPE', 'application/x-www-form-urlencoded');

        switch ($contentType) {
            // could be expanded to different content types

            default:
                parse_str($rawPayload, $payload);
        }

        return $payload;
    }

    /**
     * @param array $list
     * @param $key
     * @param $default
     * @return mixed
     */
    protected function getKeyValue(array $list, $key, $default)
    {
        return (isset($list[$key])) ? $list[$key] : $default;
    }
}

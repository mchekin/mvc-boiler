<?php

namespace Mchekin\MVCBoiler\Http;

class Response implements ResponseInterface
{

    private $statusCode = 200;
    private $headers = [
        'Content-Type' => 'text/html; charset=utf-8',
    ];
    private $session =[];

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setHeader($name, $value){
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setSession($name, $value){
        $this->session[$name] = $value;
        return $this;
    }

    /**
     * @param $contentType
     * @return $this
     */
    public function setContentType($contentType)
    {
        $this->headers['Content-Type'] = $contentType;
        return $this;
    }

    /**
     * @param $body
     */
    public function dispatch($body)
    {
        // set HTTP response code
        http_response_code($this->statusCode);

        // set HTTP headers
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }

        // creating Session data
        if (!empty($this->session)) {

            if( !isset( $_SESSION ) ) {
                session_start();
            }

            foreach ($this->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        // output the body
        echo $body;
    }

    /**
     * @param $url
     */
    public function redirect($url)
    {
        $this
            ->setHeader('Location', $url)
            ->setStatusCode(301)
            ->dispatch('');
    }
}

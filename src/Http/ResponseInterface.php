<?php

namespace Mchekin\MVCBoiler\Http;

interface ResponseInterface
{
    public function setStatusCode($statusCode);
    public function setHeader($name, $value);
    public function setSession($name, $value);
    public function setContentType($contentType);
    public function dispatch($body);
    public function redirect($url);
}

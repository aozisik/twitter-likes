<?php
namespace App\Domain\Twitter;

class Request
{
    public function __construct($method, $endpoint, $parameters = [])
    {
        $this->client = resolve('twitterClient');
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->parameters = $parameters;
    }

    public function make($parameters = [])
    {
        $parameters = array_merge($this->parameters, $parameters);
        return $this->client->{$this->method}($this->endpoint, $parameters);
    }
}

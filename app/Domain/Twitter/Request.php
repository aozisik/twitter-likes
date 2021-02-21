<?php

namespace App\Domain\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Request
{
    /**
     * @var TwitterOAuth
     */
    private $client;

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

        $response = $this->client->{$this->method}($this->endpoint, $parameters);
        $lastHttpCode = $this->client->getLastHttpCode();

        if ($lastHttpCode === 200 || $lastHttpCode === 201) {
            return $response;
        }

        logger()->debug([
            'code' => $lastHttpCode,
            'response' => json_encode($response),
        ]);

        throw new HttpException($lastHttpCode, optional($response)->error);
    }
}

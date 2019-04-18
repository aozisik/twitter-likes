<?php
namespace App\Domain\Twitter;

use Exception;
use Illuminate\Support\Facades\Log;

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
        $response = $this->client->{$this->method}($this->endpoint, $parameters);

        if (property_exists($response, 'errors')) {
            Log::error($response->errors);
            throw new Exception('Error fulfilling request!');
        }

        return $response;
    }
}

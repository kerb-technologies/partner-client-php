<?php

namespace Kerb\Partner;

use Kerb\Partner\Exceptions\EmptyApiKey;

/**
 * This is abscartion class for all Kerb Requests
 */
abstract class Request extends SetGet
{
    abstract public function getPath(): string;
    abstract public function getMethod(): string;

    /**
     * custom header which been copied for a request
     * @var array
     */
    private $headers = [];

    public function __construct(array $options)
    {
        // data came from SetGet class
        $this->data = $options;
    }

    /**
     * Validate a request, usefull for checking any requeirement ie: body, quertystring before doing a request
     *
     * @return bool
     */
    public function validate() : bool
    {
        return true;
    }

    /**
     * Set custom header which been used on request
     *
     * @param string $name name of the header
     * @param mixed $key value for the header name
     */
    final public function setHeader(string $name, $key): void
    {
        $this->headers[$name] = $key;
    }

    /**
     * Get options for use before request
     * see: https://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @return array
     */
    final public function getOptions(): array
    {
        // override header value
        $headers = $this->headers;
        $headers['Kerb-Partner-Version'] = Partner::getVersion();
        $headers['Authorization'] = 'Bearer ' . Partner::getApiKey();

        $options = $this->data;
        $options['headers'] = $headers;

        return $options;
    }
}

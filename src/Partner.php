<?php

namespace Kerb\Partner;

use GuzzleHttp\Client;

final class Partner
{
    const KERB_TOKEN_HEADER_NAME = 'KERB-PARTNER-TOKEN';
    const KERB_APIKEY_EXPIRE_AT_NAME = 'KERB-APIKEY-EXPIRE-AT';


    /**
     * @var string
     */
    private static $apiKey = '';

    /**
     * check for client custom param
     * https://docs.guzzlephp.org/en/stable/quickstart.html#making-a-request
     * this usefull for some mock or custom handler on Client params
     *
     * @var array
     */
    public static $clientParams = [];

    /**
     * This property for checking on rotate api key
     * If this property set with true, an Kerb\Exceptions\RotatedApikey will be thrown
     *
     * @var bool
     */
    public static $throwAtRotateKey = false;

    /**
     * get version
     * This value must be equal with VERSION file
     */
    public static function getVersion(): string
    {
        return '0.0.1';
    }

    /**
     * get api host
     * this method read ENV[KERB_PARTNER_HOST] value and used as baseUri on requests
     *
     * @return string
     */
    public static function getApiHost(): string
    {
        return getenv('KERB_PARTNER_HOST');
    }

    /**
     * Set api key for the request
     *
     * @param string $apiKey api key, generated from kerb partner dashboard
     */
    public static function setApiKey(string $apiKey): void
    {
        self::$apiKey = $apiKey;
    }

    /**
     * Get api key
     *
     * @return string
     */
    public static function getApiKey(): string
    {
        return self::$apiKey;
    }

    /**
     * Create a custom kerb request
     *
     * @param string $name name of the request, name will refered into Kerb\Partner\Requests namespaces
     * @param array $options options while construct the request name
     *
     * @return Request
     * @throws Exceptions\InvalidRequestName
     */
    public static function makeRequest(string $name, array $options = []): Request
    {
        $name = ucwords($name);
        $className = '\\' . __NAMESPACE__ . '\Requests\\' . $name;
        if (class_exists($className)) {
            return new $className($options);
        }

        throw new Exceptions\InvalidRequestName;
    }

    /**
     * checkRotatedKey
     *
     * @throws Exceptions\RotatedApiKey
     */
    private static function checkRotatedKey($response): void
    {
        if (! $response->hasHeader(self::KERB_APIKEY_EXPIRE_AT_NAME)) {
            return;
        }

        $expiredAt = $response->getHeader(self::KERB_APIKEY_EXPIRE_AT_NAME)[0];
        throw new Exceptions\RotatedApiKey($expiredAt);
    }

    /**
     * do a real request into kerb partner endpoin
     *
     * @param Request $request
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public static function request(Request $request)
    {
        $apiHost = self::getApiHost();
        // check api host
        if (! $apiHost) {
            throw new Exceptions\EmptyApiHost;
        }

        if (! self::getApiKey()) {
            throw new Exceptions\EmptyApiKey;
        }

        // this may raise an exception
        $request->validate();

        // check for client custom param
        // this is usefull for mocking a request while test
        // https://docs.guzzlephp.org/en/stable/quickstart.html#making-a-request
        $clientParams = array_merge(
            self::$clientParams,
            [
                'base_uri' => $apiHost,
            ]
        );
        $client = new Client($clientParams);

        /* https://docs.guzzlephp.org/en/stable/request-options.html */
        $response = $client->request(
            $request->getMethod(),
            $request->getPath(),
            $request->getOptions()
        );

        // check for expired api key
        if (self::$throwAtRotateKey) {
            self::checkRotatedKey($response);
        }

        return $response;
    }

    /**
     * send a request with default options of request name
     *
     * @param string $name name of the request
     * @param array $options options which used for buildthe request
     *
     * @return \GuzzleHttp\Psr7\Response
     * @see Partner::request
     */
    public static function send(string $name, array $options = [])
    {
        $request = self::makeRequest($name, $options);

        return self::request($request);
    }
}

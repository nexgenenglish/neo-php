<?php

namespace DynEd\Neo;

use DynEd\Neo\HttpClients\HttpClientInterface;

abstract class AbstractApi
{
    /**
     * HTTP client
     *
     * @var HttpClientInterface
     */
    protected static $httpClient;

    /**
     * Error message when HTTP client not setup yet
     *
     * @var string
     */
    protected static $errHttpClient = "setup http client";

    /**
     * Setup
     *
     * @param HttpClientInterface $httpClient
     */
    public static function useHttpClient(HttpClientInterface $httpClient)
    {
        self::$httpClient = $httpClient;
    }

    protected static function isHttpClientSetup
}
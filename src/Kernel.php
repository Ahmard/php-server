<?php

namespace PHPServer;

class Kernel
{
    protected static array $httpMiddlewares = [];

    protected static array $routeMiddlewares = [];

    /**
     * @return array
     */
    public static function getHttpMiddlewares(): array
    {
        return self::$httpMiddlewares;
    }

    /**
     * @return array
     */
    public static function getRouteMiddlewares(): array
    {
        return self::$routeMiddlewares;
    }
}
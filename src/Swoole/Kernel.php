<?php

namespace PHPServer\Swoole;

class Kernel extends \PHPServer\Kernel
{
    public static array $httpMiddlewares = [
        # AuthenticateRequestMiddleware::class,
        # RouteMiddlewareExecutorMiddleware::class,
    ];

    public static array $routeMiddlewares = [
        # 'auth' => AuthMiddleware::class,
    ];
}
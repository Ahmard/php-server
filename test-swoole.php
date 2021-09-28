<?php

use PHPServer\Swoole\Http\Request;
use PHPServer\Swoole\Server;

require 'vendor/autoload.php';

$handler = function (Request $request) {
    $html = 'Welcome,<br/>';
    $html .= "Method: {$request->getMethod()}<br/>";
    $html .= "Route: {$request->getUri()->getPath()}";
    $request->response()->html($html);
};

$paths = [
    __DIR__,
    __FILE__,
];

Server::create('127.0.0.1', 9904)
    //->watchFilesystemChanges($paths) // Requires "ahmard/swotch" package
    ->onRequest($handler)
    ->setServerConfig([
        'enable_static_handler' => true,
        'http_parse_post' => true,
        'worker_num' => 8,
        'package_max_length' => 10 * 1024 * 1024
    ])
    ->start()
    ->logOutputToConsole();
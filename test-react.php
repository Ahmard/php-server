<?php

use PHPServer\React\Server;
use Psr\Http\Message\RequestInterface;
use React\Http\Message\Response;

require 'vendor/autoload.php';

$handler = function (RequestInterface $request) {
    $html = 'Welcome,<br/>';
    $html .= "Method: {$request->getMethod()}<br/>";
    $html .= "Route: {$request->getUri()->getPath()}";
    return new Response(200, ['Content-Type' => 'text/html'], $html);
};

Server::create('127.0.0.1', 9001)
    ->onRequest($handler)
    ->start();
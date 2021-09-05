<?php

use PHPServer\ServerInfo;
use PHPServer\Terminal;
use React\Http\HttpServer;
use React\Socket\SocketServer;

require 'vendor/autoload.php';

$arguments = Terminal::performServerChecks();

/**@var ServerInfo $serverInfo * */
$serverInfo = $arguments['info'];

$socket = new SocketServer("{$serverInfo->getHost()}:{$serverInfo->getPort()}");
$http = new HttpServer($serverInfo->getRequestCallback());
$http->listen($socket);
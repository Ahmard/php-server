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
$httpServer = new HttpServer($serverInfo->getRequestCallback());

echo "ReactPHP server started at http://{$serverInfo->getHost()}:{$serverInfo->getPort()}\n";

$httpServer->listen($socket);
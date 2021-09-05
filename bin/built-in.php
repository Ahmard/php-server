<?php

use PHPServer\ServerInfo;
use PHPServer\Terminal;

require 'vendor/autoload.php';

$arguments = Terminal::performServerChecks(true);

/**@var ServerInfo $serverInfo * */
$serverInfo = $arguments['info'];


if (null !== $serverInfo->getRequestCallback()) {
    $serverInfo->getRequestCallback()();
}
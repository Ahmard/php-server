<?php

use PHPServer\BuiltIn\Server;

require 'vendor/autoload.php';

Server::create('0.0.0.0', 9904)
    ->onRequest(fn() => var_dump('Request Received'))
    ->setWorkers(3)
    ->setPHPExecutable('/usr/bin/php8.0')
    ->start()
    ->logOutputToConsole();
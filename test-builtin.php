<?php

use PHPServer\BuiltIn\Server;

require 'vendor/autoload.php';

Server::create('127.0.0.1', '9904')
    ->onRequest(fn() => var_dump('Request Received'))
    ->start()
    ->logOutputToConsole();
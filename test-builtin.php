<?php

use PHPServer\BuiltIn\Server;

require 'vendor/autoload.php';

Server::create('127.0.0.1', '9901')
    ->setDocumentRoot(__DIR__)
    ->start();

Server::create('127.0.0.1', '9902')
    ->setRouterScript('index.php')
    ->start();

Server::create('127.0.0.1', '9903')
    ->onRequest(fn() => var_dump('Request Received'))
    ->start();
<?php

use PHPServer\BuiltIn\Server;

require 'vendor/autoload.php';

Server::create('127.0.0.1', '9900')
    ->setDocumentRoot(__DIR__)
    ->start();

Server::create('127.0.0.1', '9901')
    ->setRouterScript('index.php')
    ->start();

Server::create('127.0.0.1', '9902')
    ->setDocumentRoot(__DIR__)
    ->start();
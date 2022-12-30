# PHP Server

A small library to help run PHP servers easily and quickly.

## Installation

```
composer require ahmard/php-server
```

## Usage

### PHP Built-In Server

An implementation of [Built-In Server](https://www.php.net/manual/en/features.commandline.webserver.php)

- With document root

```php
use PHPServer\BuiltIn\Server;

Server::create('127.0.0.1', '9900')
    ->setDocumentRoot(__DIR__)
    ->start()
    ->logOutputToConsole();
```

- Route request to single entry file

```php
use PHPServer\BuiltIn\Server;

Server::create('127.0.0.1', '9900')
    ->setRouterScript(__DIR__ . 'public/index.php')
    ->start();
```

- Provide callable to be invoked when request is received

```php
use PHPServer\BuiltIn\Server;

Server::create('127.0.0.1', '9900')
    ->onRequest(fn() => var_dump('Request Received'))
    ->start();
```

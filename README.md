# PHP Server

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
    ->start();
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


### ReactPHP
An implementation of [ReactPHP](https://reactphp.org)

```php
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
```
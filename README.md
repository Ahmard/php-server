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
    ->start()
    ->logOutputToConsole();
```

### Swoole
An implementation of [Swoole](https://swoole.co.uk)

```php
use PHPServer\Swoole\Http\Request;
use PHPServer\Swoole\Server;

require 'vendor/autoload.php';

$handler = function (Request $request) {
    $html = 'Welcome,<br/>';
    $html .= "Method: {$request->getMethod()}<br/>";
    $html .= "Route: {$request->getUri()->getPath()}";
    $request->response()->html($html);
};

Server::create('127.0.0.1', 9904)
    ->watchFilesystemChanges([__DIR__])
    ->onRequest($handler)
    ->setServerConfig([
        'enable_static_handler' => true,
        'http_parse_post' => true,
        'worker_num' => 8,
        'package_max_length' => 10 * 1024 * 1024
    ])
    ->start()
    ->logOutputToConsole();
```
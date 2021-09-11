<?php

use Dotenv\Dotenv;
use PHPServer\Env;
use PHPServer\ServerInfo;
use PHPServer\Swoole\Console;
use PHPServer\Swoole\Http\Request;
use PHPServer\Swoole\Http\Response;
use PHPServer\Terminal;
use Swoole\Http\Request as SWRequest;
use Swoole\Http\Response as SWResponse;
use Swoole\Http\Server;
use Swoole\Runtime;
use Swotch\Watcher;

require 'vendor/autoload.php';

$swooleData = Terminal::getArgumentAndDecode('s', 'swoole');
$serverData = Terminal::getArgumentAndDecode('i', 'server-info');

$serverInfo = ServerInfo::create($serverData);

function handleException(Throwable $exception, ?Request $request = null): void
{
    try {
        $request?->response()->html($exception);
    } catch (Throwable $exception) {
        if (!Env::isInProduction()) {
            $request->response()->html($exception);
            return;
        }

        $request->response()->html('Internal Server Error');
    } finally {
        Console::error($exception);
    }
}

set_exception_handler('handleException');

try {

    if (null !== $serverInfo->getEnvDirectory()) {
        $dotEnv = Dotenv::createImmutable(__DIR__)->load();
    }

    //Runtime configuration
    Runtime::enableCoroutine(true, SWOOLE_HOOK_ALL);

    $server = new Server($serverInfo->getHost(), $serverInfo->getPort());

    $server->on(
        'request',
        function (SWRequest $swRequest, SWResponse $swResponse) use ($serverInfo): void {
            $response = new Response($swResponse);
            $request = new Request($swRequest, $response);
            $response->setRequest($request);
            $serverInfo->getRequestCallback()($request);
        }
    );

    $server->on('start', function (Server $server) use ($serverInfo, $swooleData) {
        Console::info("Swoole server started at http://{$serverInfo->getHost()}:{$serverInfo->getPort()}");

        file_put_contents(__DIR__ . '/.pid', $server->getMasterPid());

        //Run file watcher
        if (!empty($swooleData['watch_filesystem_changes'])) {
            go(function () use ($server, $swooleData) {
                Watcher::watch($swooleData['watch_filesystem_changes'])
                    ->onAny(fn($e) => $server->reload());
            });
        }
    });

    $server->set(\PHPServer\Swoole\Server::cleanSwooleServerConfig($swooleData));

    $server->start();
} catch (Error $error) {
    dump($error);
}
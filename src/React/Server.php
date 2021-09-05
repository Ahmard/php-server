<?php

namespace PHPServer\React;

use Closure;
use JetBrains\PhpStorm\Pure;
use PHPServer\ServerCommand;
use PHPServer\ServerInfo;
use PHPServer\ServerInterface;
use PHPServer\ServerProcess;
use PHPServer\StartedServer;
use PHPServer\StartedServerInterface;
use PHPServer\Terminal;
use function PHPServer\base_path;

class Server implements ServerInterface
{
    protected string|null $documentRoot = null;
    protected string|null $routerScript = null;
    protected Closure|null $requestCallback = null;

    public function __construct(
        protected string $host,
        protected int    $port
    )
    {
    }

    #[Pure] public static function create(string $host, int $port): static
    {
        return new Server($host, $port);
    }

    public function setDocumentRoot(string $path): static
    {
        $this->documentRoot = $path;
        return $this;
    }

    public function onRequest(callable $callback): static
    {
        $this->requestCallback = $callback;
        return $this;
    }

    public function start(): StartedServerInterface
    {
        return new StartedServer($this, ServerProcess::create($this));
    }

    public function getCommand(): ServerCommand
    {
        $serverInfo = Terminal::encodeArgument($this->getInfo()->toArray());

        return ServerCommand::create('php ' . base_path('bin/react.php'))
            ->addArgument('-s', $serverInfo);
    }

    public function getInfo(): ServerInfo
    {
        return ServerInfo::create()
            ->setHost($this->host)
            ->setPort($this->port)
            ->setDocumentRoot($this->documentRoot)
            ->setRequestCallback($this->requestCallback)
            ->setRouterScript($this->routerScript);
    }
}
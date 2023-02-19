<?php

namespace PHPServer;

use Closure;
use JetBrains\PhpStorm\Pure;

abstract class AbstractServer implements ServerInterface
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
        return new static($host, $port);
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

    public function start(): RunningServer
    {
        return new RunningServer($this, ServerProcess::create($this));
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
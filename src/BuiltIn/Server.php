<?php

namespace PHPServer\BuiltIn;

use Closure;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Server
{
    protected string|null $documentRoot = null;
    protected string|null $routerScript = null;
    protected Closure|null $requestCallback = null;


    #[Pure] public static function create(float|string $host, int $port): Server
    {
        return new Server($host, $port);
    }

    public function __construct(
        protected float|string $host,
        protected int $port
    )
    {
    }

    public function setDocumentRoot(string $path): Server
    {
        $this->documentRoot = $path;
        return $this;
    }

    public function setRouterScript(string $filePath): Server
    {
        $this->routerScript = $filePath;
        return $this;
    }

    public function onRequest(callable $callback): Server
    {
        $this->requestCallback = $callback;
        return $this;
    }

    public function start(): StartedServer
    {
        return new StartedServer($this, ServerProcess::create($this));
    }

    #[ArrayShape(['host' => "int|string", 'port' => "int", 'document_root' => "null|string", 'router_script' => "null|string", 'request_callback' => "\Closure|null"])]
    public function getInfo(): array
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'document_root' => $this->documentRoot,
            'router_script' => $this->routerScript,
            'request_callback' => $this->requestCallback,
        ];
    }
}
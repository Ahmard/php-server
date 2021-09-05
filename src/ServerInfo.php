<?php

namespace PHPServer;

use Closure;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class ServerInfo
{
    protected string $host;
    protected int $port;
    protected string|null $documentRoot = null;
    protected string|null $routerScript = null;
    protected Closure|null $requestCallback = null;


    #[Pure] public static function create(): static
    {
        return new static();
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return ServerInfo
     */
    public function setHost(string $host): static
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return ServerInfo
     */
    public function setPort(int $port): static
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentRoot(): ?string
    {
        return $this->documentRoot;
    }

    /**
     * @param string|null $documentRoot
     * @return ServerInfo
     */
    public function setDocumentRoot(?string $documentRoot): static
    {
        $this->documentRoot = $documentRoot;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRouterScript(): ?string
    {
        return $this->routerScript;
    }

    /**
     * @param string|null $routerScript
     * @return ServerInfo
     */
    public function setRouterScript(?string $routerScript): static
    {
        $this->routerScript = $routerScript;
        return $this;
    }

    /**
     * @return Closure|null
     */
    public function getRequestCallback(): ?Closure
    {
        return $this->requestCallback;
    }

    /**
     * @param Closure|null $requestCallback
     * @return ServerInfo
     */
    public function setRequestCallback(?Closure $requestCallback): static
    {
        $this->requestCallback = $requestCallback;
        return $this;
    }

    #[ArrayShape(['host' => "string", 'port' => "int", 'document_root' => "null|string", 'router_script' => "null|string", 'request_callback' => "\Closure|null"])]
    public function toArray(): array
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
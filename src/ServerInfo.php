<?php

namespace PHPServer;

use Closure;
use JetBrains\PhpStorm\ArrayShape;

class ServerInfo
{
    protected string $host;
    protected int $port;
    protected string|null $documentRoot = null;
    protected string|null $routerScript = null;
    protected string|null $envDirectory = null;
    protected Closure|null $requestCallback = null;


    public static function create(array $data = []): ServerInfo
    {
        if ([] !== $data) {
            return (new ServerInfo())
                ->setHost($data['host'])
                ->setPort($data['port'])
                ->setDocumentRoot($data['document_root'])
                ->setRequestCallback($data['request_callback'])
                ->setRouterScript($data['router_script'])
                ->setEnvDirectory($data['env_directory']);
        }

        return new ServerInfo();
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
    public function setHost(string $host): ServerInfo
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
    public function setPort(int $port): ServerInfo
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
    public function setDocumentRoot(?string $documentRoot): ServerInfo
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
    public function setRouterScript(?string $routerScript): ServerInfo
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
    public function setRequestCallback(?Closure $requestCallback): ServerInfo
    {
        $this->requestCallback = $requestCallback;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnvDirectory(): ?string
    {
        return $this->envDirectory;
    }

    /**
     * @param string|null $envDirectory
     * @return static
     */
    public function setEnvDirectory(?string $envDirectory): static
    {
        $this->envDirectory = $envDirectory;
        return $this;
    }

    #[ArrayShape([
        'host' => "string",
        'port' => "int",
        'document_root' => "null|string",
        'env_directory' => "null|string",
        'router_script' => "null|string",
        'request_callback' => "\Closure|null"
    ])]
    public function toArray(): array
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'document_root' => $this->documentRoot,
            'env_directory' => $this->envDirectory,
            'router_script' => $this->routerScript,
            'request_callback' => $this->requestCallback,
        ];
    }
}
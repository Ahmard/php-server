<?php

namespace PHPServer;

interface ServerInterface
{
    public function __construct(string $host, int $port);

    /**
     * Creates server instance
     *
     * @param string $host
     * @param int $port
     * @return static
     */
    public static function create(string $host, int $port): static;

    /**
     * Sets server document root
     *
     * @param string $path
     * @return $this
     */
    public function setDocumentRoot(string $path): static;

    /**
     * Provide a callback to be executed whenever new request is received
     *
     * @param callable $callback
     * @return $this
     */
    public function onRequest(callable $callback): static;

    /**
     * Run server
     *
     * @return RunningServer
     */
    public function start(): RunningServer;

    /**
     * Gets server command instance
     *
     * @return ServerCommand
     */
    public function getCommand(): ServerCommand;

    /**
     * Gets server information
     *
     * @return ServerInfo
     */
    public function getInfo(): ServerInfo;
}
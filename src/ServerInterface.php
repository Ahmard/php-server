<?php

namespace PHPServer;

interface ServerInterface
{
    public function __construct(string $host, int $port);

    public static function create(string $host, int $port): static;

    public function setDocumentRoot(string $path): static;

    public function onRequest(callable $callback): static;

    public function start(): StartedServerInterface;

    public function getCommand(): ServerCommand;

    public function getInfo(): ServerInfo;
}
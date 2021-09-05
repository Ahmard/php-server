<?php

namespace PHPServer\BuiltIn;

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
    protected static array $filledInfo = [];
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

    public function getCommand(): ServerCommand
    {
        $config = $this->getInfo();
        $command = "php -S {$config->getHost()}:{$config->getPort()}";

        if (null == $config->getDocumentRoot()) {

            if (null !== $config->getRouterScript()) {
                $command .= " {$config->getRouterScript()}";
            }

            if (null !== $config->getRequestCallback()) {
                $s = Terminal::encodeArgument($this->getInfo()->toArray());
                $command = 'PHP_SERVER_INFO="' . $s . '" ' . $command;
                $command .= ' ' . base_path('bin/built-in.php');
            }

        }

        $serverCommand = ServerCommand::create($command);

        if (null !== $config->getDocumentRoot()) {
            $serverCommand->addArgument('-t', $config->getDocumentRoot());
        }

        return $serverCommand;
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

    public function setDocumentRoot(string $path): static
    {
        $this->documentRoot = $path;
        return $this;
    }

    public function setRouterScript(string $filePath): static
    {
        $this->routerScript = $filePath;
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
}
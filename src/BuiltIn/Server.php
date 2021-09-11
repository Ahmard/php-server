<?php

namespace PHPServer\BuiltIn;

use Closure;
use PHPServer\AbstractServer;
use PHPServer\ServerCommand;
use PHPServer\Terminal;
use function PHPServer\base_path;

class Server extends AbstractServer
{
    protected static array $filledInfo = [];
    protected string|null $documentRoot = null;
    protected string|null $routerScript = null;
    protected Closure|null $requestCallback = null;


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
}
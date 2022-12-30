<?php

namespace PHPServer\BuiltIn;

use Closure;
use PHPServer\AbstractServer;
use PHPServer\ServerCommand;
use PHPServer\Terminal;
use function PHPServer\base_path;

class Server extends AbstractServer
{
    protected string|null $documentRoot = null;
    protected string|null $routerScript = null;
    protected Closure|null $requestCallback = null;
    protected int|null $workers = null;


    public function getCommand(): ServerCommand
    {
        $config = $this->getInfo();
        $command = "php -S {$config->getHost()}:{$config->getPort()}";

        if (null != $this->workers) {
            $command = "PHP_CLI_SERVER_WORKERS=$this->workers $command";
        }


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

    /**
     * @param string|null $routerScript
     * @return static
     */
    public function setRouterScript(?string $routerScript): static
    {
        $this->routerScript = $routerScript;
        return $this;
    }

    /**
     * @param int $num
     * @return $this
     */
    public function setWorkers(int $num): static
    {
        $this->workers = $num;
        return $this;
    }
}
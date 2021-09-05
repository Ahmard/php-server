<?php

namespace PHPServer;

use React\ChildProcess\Process;

class StartedServer implements StartedServerInterface
{
    public function __construct(
        protected ServerInterface $server,
        protected Process         $process
    )
    {
    }

    /**
     * @return Process
     */
    public function getProcess(): Process
    {
        return $this->process;
    }

    /**
     * @return ServerInterface
     */
    public function getServer(): ServerInterface
    {
        return $this->server;
    }
}
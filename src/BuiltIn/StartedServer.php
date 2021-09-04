<?php

namespace PHPServer\BuiltIn;

use React\ChildProcess\Process;

class StartedServer
{
    public function __construct(
        protected Server $server,
        protected Process $process
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
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }
}
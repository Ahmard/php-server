<?php

namespace PHPServer;

use React\ChildProcess\Process;
use React\Stream\WritableResourceStream;

class RunningServer
{
    public function __construct(
        protected ServerInterface $server,
        protected Process         $process
    )
    {
    }

    public function logOutputToConsole(): static
    {
        $stdout = new WritableResourceStream(STDOUT);
        $stderr = new WritableResourceStream(STDERR);

        $this->getProcess()->stdout->pipe($stdout);
        $this->getProcess()->stderr->pipe($stderr);

        return $this;
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
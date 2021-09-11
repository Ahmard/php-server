<?php

namespace PHPServer;

use Closure;
use React\ChildProcess\Process;
use React\Stream\WritableResourceStream;

class RunningServer
{
    protected Closure $closure;

    public function __construct(
        protected ServerInterface $server,
        protected Process         $process
    )
    {
    }

    /**
     * Log server console message directly to your console
     *
     * @return $this
     */
    public function logOutputToConsole(): static
    {
        $stdout = new WritableResourceStream(STDOUT);
        $stderr = new WritableResourceStream(STDERR);

        // Handles server's console data
        $this->getProcess()->stdout->pipe($stdout);
        // Handles server's console error
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
     * Listens to server's console message
     *
     * @param Closure $callback A callback to be invoked when console message is received
     * @return $this
     */
    public function onStdout(Closure $callback): static
    {
        $this->getProcess()
            ->stdout
            ->on('data', fn(string $data) => $callback($data));

        return $this;
    }

    /**
     * @return ServerInterface
     */
    public function getServer(): ServerInterface
    {
        return $this->server;
    }
}
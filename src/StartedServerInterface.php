<?php

namespace PHPServer;

use React\ChildProcess\Process;

interface StartedServerInterface
{
    public function logOutputToConsole(): static;

    public function getProcess(): Process;

    public function getServer(): ServerInterface;
}
<?php

namespace PHPServer;

use React\ChildProcess\Process;

interface StartedServerInterface
{
    public function getProcess(): Process;

    public function getServer(): ServerInterface;
}
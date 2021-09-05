<?php

namespace PHPServer;

use React\ChildProcess\Process;
use function str_contains;

class ServerProcess
{
    public static function create(ServerInterface $server): Process
    {
        $command = self::constructCommand($server->getCommand());

        $process = new Process($command);
        $process->start();

        return $process;
    }

    protected static function constructCommand(ServerCommand $serverCommand): string
    {
        $command = $serverCommand->getCommand();
        foreach ($serverCommand->getArguments() as $name => $value) {
            $command .= " $name" . (!str_contains($name, '--') ? " \"$value\"" : "\"$value\"");
        }

        return $command;
    }
}
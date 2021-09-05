<?php

namespace PHPServer;

use React\ChildProcess\Process;
use function str_contains;

class ServerProcess
{
    public static function create(ServerInterface $server): Process
    {
        $command = self::constructCommand($server->getCommand());

        var_dump(exec($command));
        die;
        $process = new Process('echo foo');
        $process->start();

        $process->stdout->on('data', function ($chunk) {
            // echo $chunk;
        });

        $process->on('exit', function ($exitCode, $termSignal) {
            //echo 'Process exited with code ' . $exitCode . PHP_EOL;
        });

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
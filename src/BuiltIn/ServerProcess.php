<?php

namespace PHPServer\BuiltIn;

use JetBrains\PhpStorm\Pure;
use React\ChildProcess\Process;

class ServerProcess
{
    public static function create(Server $server)
    {
        dump(self::getConsoleCommand($server));
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

    #[Pure] protected static function getConsoleCommand(Server $server): string
    {
        $config = $server->getInfo();
        $command = "php -S {$config['host']}:{$config['port']}";

        if (isset($config['document_root'])) {
            $command .= " -t {$config['document_root']}";
        } else {
            if (isset($config['router_script'])) {
                $command .= " {$config['router_script']}";
            }
        }

        return $command;
    }
}
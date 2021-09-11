<?php

namespace PHPServer\React;

use PHPServer\AbstractServer;
use PHPServer\ServerCommand;
use PHPServer\Terminal;
use function PHPServer\base_path;

class Server extends AbstractServer
{
    public function getCommand(): ServerCommand
    {
        $serverInfo = Terminal::encodeArgument($this->getInfo()->toArray());

        return ServerCommand::create('php ' . base_path('bin/react.php'))
            ->addArgument('-s', $serverInfo);
    }
}
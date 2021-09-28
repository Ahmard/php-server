<?php

namespace PHPServer\Swoole;

use PHPServer\AbstractServer;
use PHPServer\ServerCommand;
use PHPServer\Terminal;
use RuntimeException;
use Swotch\Watcher;
use function PHPServer\base_path;

class Server extends AbstractServer
{
    protected array $serverConfig = [];


    public static function cleanSwooleServerConfig(array $config): array
    {
        unset(
            $config['watch_filesystem_changes'],
        );

        return $config;
    }

    /**
     * This will require an installation of "ahmard/swotch" package
     *
     * @param array $paths
     * @return $this
     */
    public function watchFilesystemChanges(array $paths): static
    {
        if (!class_exists(Watcher::class)) {
            throw new RuntimeException('Filesystem watcher requires an installation of "ahmard/swotch"');
        }

        $this->serverConfig['watch_filesystem_changes'] = $paths;
        return $this;
    }

    public function setServerConfig(array $config): static
    {
        $this->serverConfig = array_merge($this->serverConfig, $config);
        return $this;
    }

    public function getCommand(): ServerCommand
    {
        $serverInfo = Terminal::encodeArgument($this->getInfo()->toArray());
        $serverConfig = Terminal::encodeArgument($this->serverConfig);

        return ServerCommand::create('php ' . base_path('bin/swoole.php'))
            ->addArgument(ServerCommand::SERVER_INFO_LONG_ARGUMENT, $serverInfo)
            ->addArgument('--swoole', $serverConfig);
    }
}
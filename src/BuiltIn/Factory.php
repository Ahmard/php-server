<?php

namespace PHPServer\BuiltIn;

use InvalidArgumentException;

class Factory
{
    public static function create(Server ...$servers): Factory
    {
        return new Factory($servers);
    }

    /**
     * @param Server[] $servers
     */
    public function __construct(protected array $servers)
    {
        foreach ($this->servers as $server) {
            if (!$server instanceof Server) {
                $serverClass = $server::class;
                throw new InvalidArgumentException("$serverClass must be an instance of " . Server::class);
            }
        }
    }

    public function serve(): void
    {
        foreach ($this->servers as $server){
            ServerProcess::create($server);
        }
    }
}
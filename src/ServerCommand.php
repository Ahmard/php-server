<?php

namespace PHPServer;

use JetBrains\PhpStorm\Pure;

class ServerCommand
{
    public const SERVER_INFO_SHORT_ARGUMENT = 'i';
    public const SERVER_INFO_LONG_ARGUMENT = '--server-info';
    protected array $arguments = [];

    public function __construct(protected string $command)
    {
    }

    #[Pure] public static function create(string $command): ServerCommand
    {
        return new ServerCommand($command);
    }

    public function addArgument(string $name, mixed $value): static
    {
        $this->arguments[$name] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    public function getArgument(string $name): mixed
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
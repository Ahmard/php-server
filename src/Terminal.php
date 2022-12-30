<?php

namespace PHPServer;

use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use function Opis\Closure\serialize;
use function Opis\Closure\unserialize;


class Terminal
{
    public static function getArgumentAndDecode(
        string       $shortName,
        array|string $longNames = []
    ): array
    {
        return self::decodeArgument(self::getArgument($shortName, $longNames));
    }

    /**
     * Decode prepared cli argument
     *
     * @param string $value
     * @return array
     */
    public static function decodeArgument(string $value): array
    {
        return unserialize(base64_decode($value));
    }

    public static function getArgument(
        string       $shortName,
        array|string $longNames = []
    ): string|null
    {
        $optValue = getopt(
            "$shortName:",
            is_string($longNames) ? ["$longNames:"] : $longNames
        );

        if (is_string($longNames) && array_key_exists($longNames, $optValue)) {
            return $optValue[$longNames];
        }

        if (array_key_exists($longNames, $optValue)) {
            return $optValue[$longNames];
        }

        return $optValue[$shortName] ?? null;
    }

    /**
     * Prepare an argument for cli usage
     *
     * @param mixed $argument
     * @return string
     */
    public static function encodeArgument(mixed $argument): string
    {
        return base64_encode(serialize($argument));
    }

    /**
     * @param bool $useEnvVars
     * @return array{info: ServerInfo}
     */
    #[ArrayShape(['original_info' => "mixed|string", 'info' => "\PHPServer\ServerInfo"])]
    public static function performServerChecks(bool $useEnvVars = false): array
    {
        $serverInfo = $useEnvVars
            ? getenv('PHP_SERVER_INFO')
            : self::getArgument('s', 'server-info');

        if ($serverInfo) $serverInfo = self::decodeArgument($serverInfo);

        if (null == $serverInfo) {
            throw new InvalidArgumentException('Server Info argument must be valid json');
        }

        return [
            'original_info' => $serverInfo,
            'info' => ServerInfo::create()
                ->setHost($serverInfo['host'])
                ->setPort($serverInfo['port'])
                ->setDocumentRoot($serverInfo['document_root'])
                ->setRequestCallback($serverInfo['request_callback'])
                ->setRouterScript($serverInfo['router_script'])
                ->setEnvDirectory($serverInfo['env_directory']),
        ];
    }
}
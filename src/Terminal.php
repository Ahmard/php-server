<?php

namespace PHPServer;

use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use function Opis\Closure\serialize;
use function Opis\Closure\unserialize;


class Terminal
{
    public static function getJsonDecodedArg(
        string $shortName,
        string $logName,
        bool   $associative = false,
        int    $depth = 512,
        int    $flags = 0
    )
    {
        $json = self::getArgument($shortName, $logName);
        if (!$json) return null;

        return json_decode($json, $associative, $depth, $flags);
    }

    public static function getArgument(string $shortName, string|null $longName = null): string|null
    {
        $optValue = getopt(
            "$shortName:",
            $longName ? ["$longName:"] : []
        );

        if ($longName && array_key_exists($longName, $optValue)) {
            return $optValue[$longName];
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
    #[ArrayShape(['info' => "\PHPServer\ServerInfo"])]
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
            'info' => ServerInfo::create()
                ->setHost($serverInfo['host'])
                ->setPort($serverInfo['port'])
                ->setDocumentRoot($serverInfo['document_root'])
                ->setRequestCallback($serverInfo['request_callback'])
                ->setRouterScript($serverInfo['router_script']),
        ];
    }

    /**
     * Decode prepared cli argument
     *
     * @param string $value
     * @return mixed
     */
    public static function decodeArgument(string $value): mixed
    {
        return unserialize(base64_decode($value));
    }
}
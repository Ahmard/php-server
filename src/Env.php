<?php


namespace PHPServer;


use JetBrains\PhpStorm\Pure;

class Env
{
    /**
     * Check if environment variable exists
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return array_key_exists($key, $_ENV);
    }

    /**
     * Get app title
     * @return string|null
     */
    #[Pure] public static function appTitle(): ?string
    {
        return self::get('APP_TITLE');
    }

    /**
     * Get environment variable
     * @param string $key
     * @param string|null $defaultValue
     * @return string|null
     */
    public static function get(string $key, ?string $defaultValue = null): ?string
    {
        return $_ENV[$key] ?? $defaultValue;
    }

    /**
     * Get site name
     * @return string|null
     */
    public static function appName(): ?string
    {
        return self::get('APP_NAME');
    }

    /**
     * Get site web url
     * @return string|null
     */
    public static function appUrl(): ?string
    {
        return self::get('APP_URL');
    }

    /**
     * Get site keywords
     * @return string|null
     */
    public static function appKeywords(): ?string
    {
        return self::get('APP_KEYWORDS');
    }

    /**
     * Get site description
     * @return string|null
     */
    public static function appDescription(): ?string
    {
        return self::get('APP_DESCRIPTION');
    }


    /**
     * Check if app is in development state
     *
     * @return bool
     */
    public static function inInDevelopment(): bool
    {
        return 'development' === Env::get('APP_ENVIRONMENT');
    }

    /**
     * Check if app is in production state
     *
     * @return bool
     */
    #[Pure] public static function isInProduction(): bool
    {
        return 'production' === Env::get('APP_ENVIRONMENT');
    }

    #[Pure] public static function inMaintenance(): bool
    {
        return 'maintenance' === Env::get('APP_ENVIRONMENT');
    }

    public static function enableDebugger(): void
    {

    }
}
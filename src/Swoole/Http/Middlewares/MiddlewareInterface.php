<?php


namespace PHPServer\Swoole\Http\Middlewares;


use Nette\Utils\JsonException;
use PHPServer\Swoole\Http\Request;
use Throwable;

interface MiddlewareInterface
{
    /**
     * This serves like reactphp's __invoke magic method,
     * it will be called when request reach this handler
     * @param Request $request
     * @throws JsonException
     * @throws Throwable
     */
    public function handle(Request $request): void;
}
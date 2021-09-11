<?php


namespace PHPServer\Swoole\Http;


use PHPServer\Swoole\Http\Middlewares\MiddlewareInterface;

class Middleware implements MiddlewareInterface
{
    /**
     * @var string[] $middlewareParams
     */
    protected array $middlewareParams = [];


    public function __construct(string $middlewareUsers = '')
    {
        if ('' !== $middlewareUsers) {
            $this->middlewareParams = (array)explode(',', $middlewareUsers);
        }
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request): void
    {
        $request->middleware()->next();
    }
}
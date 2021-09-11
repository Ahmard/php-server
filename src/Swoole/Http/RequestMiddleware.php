<?php


namespace PHPServer\Swoole\Http;


use PHPServer\Swoole\Http\Middlewares\MiddlewareInterface;
use SplQueue;

class RequestMiddleware
{
    protected SplQueue $queue;

    public function __construct(protected Request $request, array $middlewares)
    {
        $this->queue = new SplQueue();

        foreach ($middlewares as $middleware) {
            $this->queue->push(is_object($middleware) ? $middleware : new $middleware);
        }

        $this->queue->rewind();
    }

    public static function create(Request $request, array $middlewares): RequestMiddleware
    {
        return new self($request, $middlewares);
    }

    public function push(MiddlewareInterface $middleware): void
    {
        $this->queue->push($middleware);
    }

    public function next(): void
    {
        $this->queue->next();
        $this->queue->current()->handle($this->request);
    }

    public function current(): MiddlewareInterface
    {
        return $this->queue->current();
    }

    public function getQueue(): SplQueue
    {
        return $this->queue;
    }
}
<?php


namespace PHPServer\Swoole\Http;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use PHPServer\Env;
use RingCentral\Psr7\Uri;
use Swoole\Http\Request as SWRequest;
use Throwable;


/**
 * Class Request
 * @package PHPServer\Swoole\Http
 * @mixin SWRequest
 */
class Request
{
    protected ?array $parsedJsonBody = null;
    protected bool $expectsJson = false;
    protected RequestMiddleware $middleware;
    protected Uri $uri;


    /**
     * @throws JsonException
     */
    public function __construct(
        protected SWRequest $request,
        protected Response  $response,
    )
    {
        $this->uri = new Uri(substr(Env::get('APP_URL'), 0, -1) . $request->server['request_uri']);

        $this->expectsJson = str_contains(
            $this->request->header['content-type']
            ?? $this->request->header['Content-Type']
            ?? '',
            'application/json'
        );

        if ($this->expectsJson()) {
            $this->parseJsonBody();
        }
    }

    public function expectsJson(): bool
    {
        return $this->expectsJson;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    public function parseJsonBody(): Request
    {
        $this->parsedJsonBody = Json::decode(
            $this->request->rawContent(),
            Json::FORCE_ARRAY
        );

        return $this;
    }

    public function __get(string $name): mixed
    {
        return $this->request->$name;
    }

    public function __call(string $name, array $arguments): mixed
    {
        return $this->request->$name(...$arguments);
    }

    /**
     * @throws Throwable
     * @throws JsonException
     */
    public function initMiddleware(array $middlewares): void
    {
        $this->middleware = new RequestMiddleware($this, $middlewares);
        $this->middleware->current()->handle($this);
    }

    /**
     * Get http url params
     *
     * @param string|null $key
     * @return array|string|null
     */
    public function get(?string $key = null): array|string|null
    {
        if (!$key) {
            return $this->request->get;
        }

        return $this->request->get[$key];
    }

    public function isGET(): bool
    {
        return 'GET' == $this->getMethod();
    }

    public function getMethod(): string
    {
        return $this->request->server['request_method'];
    }

    public function isPOST(): bool
    {
        return 'POST' == $this->getMethod();
    }

    public function getUri(): Uri
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getParsedBody(): array
    {
        $data = $this->post();
        return !is_array($data) ? [] : $data;
    }

    /**
     * Get http post data, json body if json is parsed
     *
     * @param string|null $key
     * @return array|string|int|float|null
     */
    public function post(?string $key = null): array|string|int|float|null
    {
        if (!$key) {
            if ($this->parsedJsonBody) {
                return $this->parsedJsonBody;
            }

            return $this->request->post;
        }

        if ($this->parsedJsonBody) {
            return $this->parsedJsonBody[$key] ?? null;
        }

        return $this->request->post[$key] ?? null;
    }

    public function getQueryParams(?string $key = null): array|string|null
    {
        return $this->getQuery($key);
    }

    /**
     * Get url query(ies)
     *
     * @param string|null $name
     * @return string|array|null
     */
    public function getQuery(?string $name = null): array|string|null
    {
        if (null !== $name) {
            return $this->get[$name] ?? null;
        }

        return $this->get;
    }

    /**
     * Check if specific url query param exists
     *
     * @param string $name
     * @return bool
     */
    public function hasQuery(string $name): bool
    {
        $queries = $this->getQuery();
        if (!is_array($queries)) return false;

        return array_key_exists($name, $queries);
    }

    public function response(): Response
    {
        return $this->response;
    }

    public function middleware(): RequestMiddleware
    {
        return $this->middleware;
    }

    public function getSwooleRequest(): SWRequest
    {
        return $this->request;
    }
}
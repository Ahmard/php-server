<?php


namespace PHPServer\Swoole\Http;


use ErrorException;
use Exception;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Swoole\Http\Response as SWResponse;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * Class Response
 * @package PHPServer\Swoole\Http
 * @mixin SWResponse
 */
class Response
{

    protected Request $request;
    protected float $started;

    public function __construct(
        protected SWResponse $response,
    )
    {
        $this->started = microtime(true);
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function __call(string $name, array $arguments): mixed
    {
        return $this->response->$name(...$arguments);
    }

    /**
     * @param string|array|object|null $encodeAble
     * @throws JsonException
     */
    public function jsonSuccess(null|string|array|object $encodeAble): void
    {
        $this->json([
            'status' => 200,
            'data' => $encodeAble,
            'success' => true
        ]);
    }

    /**
     * @param array|object|null $encodeAble
     * @param int $status
     * @param array $headers
     * @throws JsonException
     */
    public function json(null|array|object $encodeAble, int $status = 200, array $headers = []): void
    {
        $this->end(
            Json::encode($encodeAble),
            $status,
            array_merge(['Content-Type' => 'application/json'], $headers)
        );
    }

    /**
     * @param mixed|null $content
     * @param int $status
     * @param array $headers
     */
    public function end(mixed $content = null, int $status = 200, array $headers = []): void
    {
        dump('Time Take: ' . microtime(true) - $this->started);
        if ($this->response->isWritable()) {
            foreach ($headers as $key => $value) {
                $this->response->setHeader($key, $value);
            }

            $this->response->setHeader('Server', 'PHPServer');
            $this->response->setStatusCode($status);
            $this->response->end($content);
        }
    }

    /**
     * @param mixed $encodeAble
     * @throws JsonException
     */
    public function jsonError(mixed $encodeAble): void
    {
        $this->json([
            'status' => 200,
            'data' => $encodeAble,
            'success' => false
        ]);
    }

    public function dd(mixed $data, mixed ...$moreData): void
    {
        $this->dump($data, ...$moreData);
        $this->response->end();
    }

    public function dump(mixed $data, mixed ...$moreData): void
    {
        $this->write(self::createDump($data));

        foreach ($moreData as $datum) {
            $this->write(self::createDump($datum));
        }
    }

    /**
     * @param mixed $data
     * @return string
     * @throws ErrorException
     */
    protected static function createDump(mixed $data): string
    {
        $clonedData = (new VarCloner())->cloneVar($data);
        return (string)(new HtmlDumper(null))->dump($clonedData, true);
    }

    /**
     * Reload webpage
     */
    public function reload(): void
    {
        $this->redirect($this->request->getUri()->getPath());
    }

    /**
     * Redirect to another webpage
     *
     * @param string $url
     */
    public function redirect(string $url): void
    {
        $this->response->setHeader('Location', $url);
        $this->end(null, 302);
    }

    /**
     * Send 404 not found response
     *
     * @throws Exception
     */
    public function notFound(): void
    {
        $this->html('Sorry, the source file seems to be missing, please contact the file provider.');
    }

    /**
     * @param string $htmlCode
     * @param int $status
     * @param array $headers
     */
    public function html(string $htmlCode, int $status = 200, array $headers = []): void
    {
        if ($this->response->isWritable()) {
            $this->end(
                $htmlCode,
                $status,
                array_merge(['Content-Type' => 'text/html'], $headers)
            );
        }
    }
}
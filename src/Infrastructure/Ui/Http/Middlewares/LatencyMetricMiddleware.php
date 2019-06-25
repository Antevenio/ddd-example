<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares;

use Antevenio\DddExample\Infrastructure\Metrics\PrometheusCollectorRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LatencyMetricMiddleware implements MiddlewareInterface
{
    /**
     * @var PrometheusCollectorRegistry
     */
    private $prometheusCollectorRegistry;

    /**
     * @var array
     */
    private $excludedPaths;

    /**
     * LatencyMetricMiddleware constructor.
     * @param PrometheusCollectorRegistry $prometheusCollectorRegistry
     */
    public function __construct(PrometheusCollectorRegistry $prometheusCollectorRegistry)
    {
        $this->prometheusCollectorRegistry = $prometheusCollectorRegistry;
        $this->excludedPaths = ['/metrics'];
    }

    /**
     * Process an incoming server request and it logs request/response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (in_array($path, $this->excludedPaths)) {
            return $handler->handle($request);
        }
        $startTime = microtime(true);
        $response = $handler->handle($request);
        $time = microtime(true) - $startTime;
        $latencyHistogram = $this->prometheusCollectorRegistry->getOrRegisterHistogram(
            'request_duration_seconds',
            'The duration of requests, in seconds',
            ['path', 'method']
        );
        $latencyHistogram->observe(
            $time,
            [$path, $request->getMethod()]
        );
        return $response;
    }
}

<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares;

use Antevenio\DddExample\Domain\Metric\MetricService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LatencyMetricMiddleware implements MiddlewareInterface
{

    /**
     * @var MetricService
     */
    private $metricService;

    /**
     * LatencyMetricMiddleware constructor.
     * @param MetricService $metricService
     */
    public function __construct(MetricService $metricService)
    {
        $this->metricService = $metricService;
    }


    /**
     * Process an incoming server request and it logs request/response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $startTime = microtime(true);
        $response = $handler->handle($request);
        $time = microtime(true) - $startTime;

        $latencyHistogram = $this->metricService->getHistogram('request_duration_seconds');
        $latencyHistogram->observe(
            $time,
            [$path, $request->getMethod()]
        );
        return $response;
    }
}

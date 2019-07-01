<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Antevenio\DddExample\Domain\Metric\MetricService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MetricsHandler implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var MetricService
     */
    private $metricService;

    /**
     * MetricsHandler constructor.
     * @param ResponseFactoryInterface $responseFactory
     * @param MetricService $metricService
     */
    public function __construct(ResponseFactoryInterface $responseFactory, MetricService $metricService)
    {
        $this->responseFactory = $responseFactory;
        $this->metricService = $metricService;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $counter = $this->metricService->getCounter('some_counter');
        $counter->increment(['blue']);

        $gauge = $this->metricService->getGauge('some_gauge');
        $gauge->set(10, ['blue']);

        $histogram = $this->metricService->getHistogram('some_histogram');
        $histogram->observe(5.5, ['blue']);

        $response = $this->responseFactory->createResponse(200);
        $response->getBody()->write($this->metricService->getMetrics());

        return $response;
    }
}

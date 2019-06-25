<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Antevenio\DddExample\Infrastructure\Metrics\PrometheusCollectorRegistry;
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
     * @var PrometheusCollectorRegistry
     */
    private $prometheusCollectorRegistry;

    /**
     * MetricsHandler constructor.
     * @param ResponseFactoryInterface $responseFactory
     * @param PrometheusCollectorRegistry $prometheusCollectorRegistry
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        PrometheusCollectorRegistry $prometheusCollectorRegistry
    ) {
        $this->responseFactory = $responseFactory;
        $this->prometheusCollectorRegistry = $prometheusCollectorRegistry;
    }


    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $counter = $this->prometheusCollectorRegistry->getOrRegisterCounter(
            'some_counter',
            'it increases',
            ['type']
        );
        $counter->incBy(1, ['blue']);

        $gauge = $this->prometheusCollectorRegistry->getOrRegisterGauge(
            'some_gauge',
            'it sets',
            ['type']
        );
        $gauge->set(10, ['blue']);

        $histogram = $this->prometheusCollectorRegistry->getOrRegisterHistogram(
            'some_histogram',
            'it observes',
            ['type'],
            [0.1, 1, 2, 3.5, 4, 5, 6, 7, 8, 9]
        );
        $histogram->observe(5.5, ['blue']);

        $response = $this->responseFactory->createResponse(200);
        $response->getBody()->write($this->prometheusCollectorRegistry->getMetrics());

        return $response;
    }
}

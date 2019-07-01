<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Antevenio\DddExample\Domain\Metric\MetricService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class MetricsHandlerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $responseFactory = $container->get(ResponseFactoryInterface::class);
        $metricService = $container->get(MetricService::class);
        return new MetricsHandler($responseFactory, $metricService);
    }
}

<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Antevenio\DddExample\Infrastructure\Metrics\PrometheusCollectorRegistry;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class MetricsHandlerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $responseFactory = $container->get(ResponseFactoryInterface::class);
        $prometheusCollectorRegistry = $container->get(PrometheusCollectorRegistry::class);
        return new MetricsHandler($responseFactory, $prometheusCollectorRegistry);
    }
}

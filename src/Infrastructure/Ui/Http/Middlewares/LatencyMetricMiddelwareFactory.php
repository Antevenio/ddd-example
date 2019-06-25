<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares;


use Antevenio\DddExample\Infrastructure\Metrics\PrometheusCollectorRegistry;
use Psr\Container\ContainerInterface;

class LatencyMetricMiddelwareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $prometheusCollectorRegistry = $container->get(PrometheusCollectorRegistry::class);
        return new LatencyMetricMiddleware($prometheusCollectorRegistry);
    }

}

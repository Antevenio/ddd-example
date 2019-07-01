<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares;

use Antevenio\DddExample\Domain\Metric\MetricService;
use Psr\Container\ContainerInterface;

class LatencyMetricMiddelwareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $metricService = $container->get(MetricService::class);
        return new LatencyMetricMiddleware($metricService);
    }
}

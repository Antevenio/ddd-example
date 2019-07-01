<?php


namespace Antevenio\DddExample\Infrastructure\Domain\Metric;

use Antevenio\DddExample\Infrastructure\Domain\Metric\Prometheus\PrometheusMetricService;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis;
use Psr\Container\ContainerInterface;

class PrometheusMetricServiceFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        Redis::setDefaultOptions($config['redis']);
        $collectorRegistry = CollectorRegistry::getDefault();

        return new PrometheusMetricService($collectorRegistry, $config['metrics']);
    }
}

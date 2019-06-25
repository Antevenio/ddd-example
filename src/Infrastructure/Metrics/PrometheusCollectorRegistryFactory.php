<?php


namespace Antevenio\DddExample\Infrastructure\Metrics;


use Psr\Container\ContainerInterface;

class PrometheusCollectorRegistryFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new PrometheusCollectorRegistry($config);
    }

}

<?php


namespace Antevenio\DddExample\Infrastructure\Metrics;


use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\Redis;

class PrometheusCollectorRegistry
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var CollectorRegistry
     */
    private $collectorRegistry;

    /**
     * PrometheusCollectorRegistry constructor.
     * @param mixed $config
     */
    public function __construct($config)
    {
        $this->namespace = $config['boundedContextName'];
        Redis::setDefaultOptions($config['redis']);
        $this->collectorRegistry = CollectorRegistry::getDefault();
    }

    public function getOrRegisterCounter(string $name, string $help, array $labels)
    {
        return $this->collectorRegistry->getOrRegisterCounter($this->namespace, $name, $help, $labels);
    }

    public function getOrRegisterGauge(string $name, string $help, array $labels)
    {
        return $this->collectorRegistry->getOrRegisterGauge($this->namespace, $name, $help, $labels);
    }

    public function getOrRegisterHistogram(
        string $name,
        string $help,
        array $labels,
        array $buckets = null
    ) {
        return $this->collectorRegistry->getOrRegisterHistogram($this->namespace, $name, $help, $labels, $buckets);
    }

    public function getMetrics() : string
    {
        $renderer = new RenderTextFormat();
        return $renderer->render($this->collectorRegistry->getMetricFamilySamples());

    }

}

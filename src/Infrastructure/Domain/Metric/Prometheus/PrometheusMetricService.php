<?php


namespace Antevenio\DddExample\Infrastructure\Domain\Metric\Prometheus;

use Antevenio\DddExample\Domain\Metric\Counter;
use Antevenio\DddExample\Domain\Metric\Gauge;
use Antevenio\DddExample\Domain\Metric\Histogram;
use Antevenio\DddExample\Domain\Metric\MetricService;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

class PrometheusMetricService implements MetricService
{

    /**
     * @var CollectorRegistry
     */
    private $collectorRegistry;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $namespace;

    /**
     * PrometheusMetricService constructor.
     * @param CollectorRegistry $collectorRegistry
     * @param array $config
     */
    public function __construct(CollectorRegistry $collectorRegistry, array $config)
    {
        $this->collectorRegistry = $collectorRegistry;
        $this->config = $config;
        $this->namespace = $config['namespace'];
    }

    /**
     * It Gets or creates a Counter
     *
     * @param $counterName
     * @return Counter
     */
    public function getCounter($name): Counter
    {
        $help = $this->getHelpFromConfig($name);
        $labels = $this->getLabelsFromConfig($name);
        return new PrometheusCounter(
            $this->collectorRegistry->getOrRegisterCounter($this->namespace, $name, $help, $labels)
        );
    }

    /**
     * It gets or creates a Gauge
     *
     * @param string $name
     * @return Gauge
     */
    public function getGauge(string $name): Gauge
    {
        $help = $this->getHelpFromConfig($name);
        $labels = $this->getLabelsFromConfig($name);
        return new PrometheusGauge(
            $this->collectorRegistry->getOrRegisterGauge($this->namespace, $name, $help, $labels)
        );
    }

    /**
     * It gets or creates an Histogram
     *
     * @param string $name
     * @return Histogram
     */
    public function getHistogram(string $name): Histogram
    {
        $help = $this->getHelpFromConfig($name);
        $labels = $this->getLabelsFromConfig($name);
        $buckets = $this->getBucketsFromConfig($name);

        return new PrometheusBucket(
            $this->collectorRegistry->getOrRegisterHistogram(
                $this->namespace,
                $name,
                $help,
                $labels,
                $buckets
            )
        );
    }

    /**
     * Get metrics in provider\'s format
     * @return string
     */
    public function getMetrics(): string
    {
        $renderer = new RenderTextFormat();
        return $renderer->render($this->collectorRegistry->getMetricFamilySamples());
    }

    private function getHelpFromConfig($name) : string
    {
        return $this->getValueFromConfig($name, 'help', '');
    }

    private function getLabelsFromConfig($name) : array
    {
        return $this->getValueFromConfig($name, 'labels', []);
    }

    private function getBucketsFromConfig(string $name)
    {
        return $this->getValueFromConfig($name, 'buckets', null);
    }
    private function getValueFromConfig($name, $property, $defaultValue)
    {
        if (!isset($this->config[$name]) || !isset($this->config[$name][$property])) {
            return $defaultValue;
        }
        return $this->config[$name][$property];
    }
}

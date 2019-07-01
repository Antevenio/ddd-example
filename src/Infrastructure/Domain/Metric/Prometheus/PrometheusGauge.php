<?php


namespace Antevenio\DddExample\Infrastructure\Domain\Metric\Prometheus;

use Antevenio\DddExample\Domain\Metric\Gauge;

class PrometheusGauge implements Gauge
{
    /**
     * @var \Prometheus\Gauge
     */
    private $gauge;

    /**
     * PrometheusCounter constructor.
     * @param \Prometheus\Gauge $gauge
     */
    public function __construct(\Prometheus\Gauge $gauge)
    {
        $this->gauge = $gauge;
    }


    public function set($value, array $labels = []): void
    {
        $this->gauge->set($value, $labels);
    }
}

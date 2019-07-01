<?php


namespace Antevenio\DddExample\Domain\Metric;

interface MetricService
{
    /**
     * It gets or creates a Counter
     *
     * @param $name
     * @return Counter
     */
    public function getCounter($name): Counter;

    /**
     * It gets or creates a Gauge
     *
     * @param string $name
     * @return Gauge
     */
    public function getGauge(string $name): Gauge;

    /**
     * It gets or creates an Histogram
     *
     * @param string $name
     * @return Histogram
     */
    public function getHistogram(string $name): Histogram;

    /**
     * Get metrics in provider\'s format
     * @return string
     */
    public function getMetrics(): string;
}

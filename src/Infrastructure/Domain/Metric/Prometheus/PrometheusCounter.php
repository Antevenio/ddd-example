<?php


namespace Antevenio\DddExample\Infrastructure\Domain\Metric\Prometheus;

use Antevenio\DddExample\Domain\Metric\Counter;

class PrometheusCounter implements Counter
{
    /**
     * @var \Prometheus\Counter
     */
    private $counter;

    /**
     * PrometheusCounter constructor.
     * @param \Prometheus\Counter $counter
     */
    public function __construct(\Prometheus\Counter $counter)
    {
        $this->counter = $counter;
    }


    public function increment(array $labels = []): void
    {
        $this->counter->inc($labels);
    }
}

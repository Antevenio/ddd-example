<?php


namespace Antevenio\DddExample\Infrastructure\Domain\Metric\Prometheus;

use Antevenio\DddExample\Domain\Metric\Histogram;

class PrometheusBucket implements Histogram
{
    /**
     * @var \Prometheus\Histogram
     */
    private $histogram;

    /**
     * PrometheusBucket constructor.
     * @param \Prometheus\Histogram $histogram
     */
    public function __construct(\Prometheus\Histogram $histogram)
    {
        $this->histogram = $histogram;
    }


    public function observe(float $value, array $labels = []): void
    {
        $this->histogram->observe($value, $labels);
    }
}

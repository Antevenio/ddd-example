<?php


namespace Antevenio\DddExample\Domain\Metric;

interface Gauge
{
    public function set($value, array $labels = []): void;
}

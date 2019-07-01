<?php


namespace Antevenio\DddExample\Domain\Metric;

interface Histogram
{
    public function observe(float $value, array $labels = []): void;
}

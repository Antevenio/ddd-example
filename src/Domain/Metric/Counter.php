<?php


namespace Antevenio\DddExample\Domain\Metric;

interface Counter
{

    public function increment(array $labels = []) : void;
}

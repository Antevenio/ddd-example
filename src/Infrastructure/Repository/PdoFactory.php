<?php

namespace Antevenio\DddExample\Infrastructure\Repository;

class PdoFactory
{
    public function __invoke($config)
    {
        return new PdoWrapper(
            $config['dsn'],
            $config['username'],
            $config['password'],
            $config['driver_options'],
            true
        );
    }
}

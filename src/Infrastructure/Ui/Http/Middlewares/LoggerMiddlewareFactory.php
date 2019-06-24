<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LoggerMiddleware($container->get(LoggerInterface::class));
    }
}

<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ErrorApiProblemHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $responseFactory = $container->get(ResponseFactoryInterface::class);
        return new ErrorApiProblemHandler($responseFactory);
    }
}

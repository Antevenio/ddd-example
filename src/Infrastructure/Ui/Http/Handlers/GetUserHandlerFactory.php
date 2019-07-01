<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Antevenio\DddExample\Application\ActionBus;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class GetUserHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $responseFactory = $container->get(ResponseFactoryInterface::class);
        $actionBus = $container->get(ActionBus::class);
        return new GetUserHandler($responseFactory, $actionBus);
    }
}

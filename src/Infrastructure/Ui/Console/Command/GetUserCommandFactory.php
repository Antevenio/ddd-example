<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Actions\ActionBus;
use Psr\Container\ContainerInterface;

class GetUserCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $actionBus = $container->get(ActionBus::class);
        return new GetUserCommand($actionBus);
    }
}

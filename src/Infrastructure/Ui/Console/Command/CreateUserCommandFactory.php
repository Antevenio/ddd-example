<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Application\ActionBus;
use Psr\Container\ContainerInterface;

class CreateUserCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $actionBus = $container->get(ActionBus::class);
        return new CreateUserCommand($actionBus);
    }
}

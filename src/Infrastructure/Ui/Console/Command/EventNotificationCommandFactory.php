<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Domain\Event\EventStore;
use Psr\Container\ContainerInterface;

class EventNotificationCommandFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $eventStore = $container->get(EventStore::class);
        return new EventNotificationCommand($eventStore);
    }
}

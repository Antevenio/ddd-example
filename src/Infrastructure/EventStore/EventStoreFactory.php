<?php


namespace Antevenio\DddExample\Infrastructure\EventStore;

use Antevenio\DddExample\Domain\Event\EventNotification;
use Antevenio\DddExample\Infrastructure\EventStore\Prooph\ProophEventStore;
use Psr\Container\ContainerInterface;

class EventStoreFactory
{
    /**
     * @var string
     */
    private $pdoServiceName;

    /**
     * EventStoreFactory constructor.
     * @param string $pdoServiceName
     */
    public function __construct(string $pdoServiceName)
    {
        $this->pdoServiceName = $pdoServiceName;
    }

    public function __invoke(ContainerInterface $container)
    {
        $pdo = $container->get($this->pdoServiceName);
        $eventNotification = $container->get(EventNotification::class);
        return new ProophEventStore(
            $pdo,
            $container->get('config')['boundedContextName'],
            $eventNotification
        );
    }
}

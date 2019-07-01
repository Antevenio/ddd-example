<?php


namespace Antevenio\DddExample\Infrastructure\EventStore\Prooph;

use ArrayIterator;
use Antevenio\DddExample\Domain\Event\EventNotification;
use Antevenio\DddExample\Domain\Event\EventStore;
use Antevenio\DddExample\Domain\Event\DomainEvent;

use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\EventStore\ActionEventEmitterEventStore;
use Prooph\EventStore\Exception\StreamNotFound;
use Prooph\EventStore\Pdo\MySqlEventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlSimpleStreamStrategy;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;

class ProophEventStore implements EventStore
{
    const LOAD_BATCH_SIZE = 1000;

    const DISABLE_TRANSACTION_HANDLING = true;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $boundedContextName;

    /**
     * @var EventNotification
     */
    private $eventNotification;

    /**
     * @var ActionEventEmitterEventStore
     */
    private $eventStore;

    /**
     * @var StreamName
     */
    private $streamName;

    /**
     * ProophEventStore constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo, string $boundedContextName, EventNotification $eventNotification)
    {
        $this->pdo = $pdo;
        $this->boundedContextName = $boundedContextName;
        $this->eventNotification = $eventNotification;
        $this->init();
    }

    private function init(): void
    {
        $eventStore = new MysqlEventStore(
            new ProophDomainEventMessageFactory(),
            $this->pdo,
            new MySqlSimpleStreamStrategy(),
            self::LOAD_BATCH_SIZE,
            $this->getEventStreamsTable(),
            self::DISABLE_TRANSACTION_HANDLING
        );
        $eventEmitter = new ProophActionEventEmitter();
        $this->eventStore = new ActionEventEmitterEventStore(
            $eventStore,
            $eventEmitter
        );
        $this->streamName = new StreamName($this->boundedContextName);
    }

    /**
     * @param DomainEvent $domainEvent
     * @return mixed
     */
    public function append(DomainEvent $domainEvent): void
    {
        try {
            $this->tryToAppend($domainEvent);
        } catch (StreamNotFound $exception) {
            $singleStream = new Stream($this->streamName, new ArrayIterator());
            $this->eventStore->create($singleStream);
            $this->tryToAppend($domainEvent);
        }
    }

    private function tryToAppend(DomainEvent $domainEvent): void
    {
        $this->eventStore->appendTo(
            $this->streamName,
            new ArrayIterator([new ProophDomainEventDecorator($domainEvent)])
        );
    }

    /**
     * @return mixed
     */
    public function notifyStoredEvents(): void
    {
        $projectionManager = new MySqlProjectionManager(
            $this->eventStore,
            $this->pdo,
            $this->getEventStreamsTable(),
            $this->getProjectionsTable()
        );

        $eventNotification = $this->eventNotification;
        $projector = $projectionManager->createProjection($this->getProjectionName());
        $projector
            ->fromStream($this->boundedContextName)
            ->whenAny(
                function (array $state, ProophDomainEventDecorator $event) use ($eventNotification) : array {
                    $eventNotification->notify($event->getDomainEvent());
                    return $state;
                }
            )
            ->run();
    }

    private function getProjectionName(): string
    {
        return $this->boundedContextName . '_projection';
    }

    private function getEventStreamsTable(): string
    {
        return $this->boundedContextName . '_event_streams';
    }

    private function getProjectionsTable(): string
    {
        return $this->boundedContextName . '_projections';
    }
}

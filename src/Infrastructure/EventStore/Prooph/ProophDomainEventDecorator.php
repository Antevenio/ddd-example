<?php


namespace Antevenio\DddExample\Infrastructure\EventStore\Prooph;

use Prooph\Common\Messaging\DomainEvent as ProophDomainEvent;
use Antevenio\DddExample\Domain\Event\DomainEvent;

class ProophDomainEventDecorator extends ProophDomainEvent
{
    /**
     * @var DomainEvent
     */
    private $domainEvent;

    /**
     * ProophDomainEventDecorator constructor.
     * @param DomainEvent $domainEvent
     */
    public function __construct(DomainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->messageName = \get_class($domainEvent);
        $this->init();
    }

    protected function setPayload(array $payload): void
    {
        $this->domainEvent = ($this->messageName)::fromArray(
            $payload
        );
    }

    public function payload(): array
    {
        return $this->domainEvent->jsonSerialize();
    }

    public function getDomainEvent(): DomainEvent
    {
        return $this->domainEvent;
    }
}
